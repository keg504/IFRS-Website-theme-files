<?php

namespace PostGallery\Controllers;

use Exception;
use WPMVC\Log;
use WPMVC\Request;
use WPMVC\Response;
use WPMVC\MVC\Controller;
use PostGallery\Models\Attachment;
/**
 * Video hooks.
 * WordPress MVC Controller.
 *
 * @author Alejandro Mostajo <http://about.me/amostajo>
 * @copyright 10Quality <http://www.10quality.com>
 * @package simple-post-gallery
 * @version 2.3.12
 */
class VideoController extends Controller
{
    /**
     * Returns a youtube ID based on a shared url.
     * @since 2.1.0
     *
     * @param string $url Provider url.
     *
     * @return int
     */
    private function get_youtube_id( $url )
    {
        parse_str( parse_url( $url, PHP_URL_QUERY ), $vars );
        return empty( $vars ) ? substr(parse_url($url, PHP_URL_PATH), 1) : $vars['v'];
    }
    /**
     * Returns a vimeo ID based on a shared url.
     * @since 2.1.0
     *
     * @param string $url Provider url.
     *
     * @return int
     */
    private function get_vimeo_id( $url )
    {
        return intval( substr( parse_url( $url, PHP_URL_PATH ), 1 ) );
    }
    /**
     * Returns media uploader tabs.
     * @since 2.1.0
     * 
     * @hook media_upload_tabs
     *
     * @param array $tabs Media tabs.
     *
     * @return array
     */
    public function media_tabs( $tabs = [] )
    {
        $tabs['video_importer'] = __( 'Import Video', 'simple-post-gallery' );
        return $tabs;
    }
    /**
     * Adds video iframe.
     * @since 2.1.0
     * 
     * @hook media_upload_video_importer
     */
    public function media_tab()
    {
        wp_iframe( [&$this, 'show_video_importer'] );
    }
    /**
     * Displays video importer tab.
     * @since 2.1.0
     */
    public function show_video_importer()
    {
        $this->view->show( 'media.video-importer', ['view' => &$this->view] );
    }
    /**
     * Ajax call for validating video URL.
     * @since 2.1.0
     * 
     * @hook wp_ajax_video_importer_validate
     */
    public function ajax_validate()
    {
        $response = new Response;
        try {
            // Prepare
            $input = [
                'url'   => Request::input( 'url' ),
            ];
            // Validation
            // -- URL check
            if ( empty( $input['url'] )
                || ! preg_match( '/http/', $input['url'] )
            ) {
                $response->error( 'url', __( 'Invalid value.', 'simple-post-gallery' ) );
                $response->message = __( 'Url provided appears to be invalid.', 'simple-post-gallery' );
            }
            // -- Supported providers
            if ( ! preg_match( apply_filters( 'post_gallery_video_providers_regx', '/youtu|vimeo/' ), $input['url'] ) ) {
                $response->error( 'url', __( 'Invalid video provider.', 'simple-post-gallery' ) );
                $response->message = __( 'Video provider not supported.', 'simple-post-gallery' );
            }
            if ( $response->passes ) {
                $response->data = [
                    'preview'   => apply_filters( 'post_gallery_video_preview', null, $input['url'], null ),
                ];
                $response->success = true;
            }
        } catch ( Exception $e ) {
            Log::error( $e );
        }
        $response->json();
    }
    /**
     * Returns video HTML preview.
     * @since 2.1.0
     * 
     * @hook post_gallery_video_preview
     *
     * @param string $html HTML preview.
     * @param string $url  URL provided to generate preview.
     *
     * @return string
     */
    public function preview( $html = [], $url, $model = null )
    {
        // YouTube
        if ( preg_match( '/youtu/', $url) ) {
            return $this->view->get(
                'media.previews.youtube',
                ['id' => $this->get_youtube_id( $url ), 'model' => &$model]
            );
        }
        // Vimeo
        if ( preg_match( '/vimeo/', $url) ) {
            return $this->view->get(
                'media.previews.vimeo',
                ['id' => $this->get_vimeo_id( $url ), 'model' => &$model]
            );
        }
        return $html;
    }
    /**
     * Imports video.
     * @since 2.1.0
     * 
     * @hook wp_ajax_video_importer_import
     */
    public function ajax_import()
    {
        $response = new Response;
        try {
            // Prepare
            $input = [
                'url'   => Request::input( 'url' ),
            ];
            // Validation
            // -- URL check
            if ( empty( $input['url'] )
                || ! preg_match( '/http/', $input['url'] )
            ) {
                $response->error( 'url', __( 'Invalid value.', 'simple-post-gallery' ) );
                $response->message = __( 'Url provided appears to be invalid.', 'simple-post-gallery' );
            }
            // -- Supported providers
            if ( ! preg_match( apply_filters( 'post_gallery_video_providers_regx', '/youtu|vimeo/' ), $input['url'] ) ) {
                $response->error( 'url', __( 'Invalid video provider.', 'simple-post-gallery' ) );
                $response->message = __( 'Video provider not supported.', 'simple-post-gallery' );
            }
            if ( $response->passes ) {
                $model = apply_filters( 'post_gallery_import_video', new Attachment, $input['url'] );
                $model->save();
                $response->data = [
                    'preview'   => $model->embed,
                ];
                $response->success = true;
            }
        } catch ( Exception $e ) {
            Log::error( $e );
        }
        $response->json();
    }
    /**
     * Returns filtered model/attachment with all video details.
     * @since 2.1.0
     * 
     * @hook post_gallery_import_video
     *
     * @param object|Attachment $model Attachment model.
     * @param string            $url   URL provided to generate preview.
     *
     * @return object|Attachment
     */
    public function import( $model, $url )
    {
        // YouTube
        if ( preg_match( '/youtu/', $url) ) {
            $id = $this->get_youtube_id( $url );
            // Get Images
            $image_url = 'https://img.youtube.com/vi/'.$id.'/maxresdefault.jpg';
            list( $width, $height ) = getimagesize( $image_url );
            if ( $height < 200 )
                $image_url = 'https://img.youtube.com/vi/'.$id.'/hqdefault.jpg';
            // SIMULATE AN UPLOAD
            $attachment_id = $this->create_from_thumbnail( $image_url, $id );
            if ( $attachment_id ) {
                $model = Attachment::find( $attachment_id );
                $model->mime = 'video/youtube';
                $model->video_id = $id;
                $model->video_url = 'https://www.youtube.com/watch?v='.$id;
                $model->video_provider = 'YouTube';
            }
        }
        // Vimeo
        if ( preg_match( '/vimeo/', $url) ) {
            $id = $this->get_vimeo_id( $url );
            // Get Images
            $hash = unserialize( file_get_contents( 'http://vimeo.com/api/v2/video/'.$id.'.php' ) );
            // SIMULATE AN UPLOAD
            $attachment_id = $this->create_from_thumbnail(
                isset( $hash[0]['thumbnail_large'] ) ? $hash[0]['thumbnail_large'] : $hash[0]['thumbnail_medium'],
                $id
            );
            if ( $attachment_id ) {
                $model = Attachment::find( $attachment_id );
                $model->mime = 'video/vimeo';
                $model->video_id = $id;
                $model->video_url = 'https://vimeo.com/'.$id;
                $model->video_provider = 'Vimeo';
            }
        }
        $model->embed = apply_filters( 'post_gallery_video_preview', null, $url, $model );
        return $model;
    }
    /**
     * Creates and attachment video from a thumbnail image.
     * @since 2.1.0
     *
     * @link https://codex.wordpress.org/Function_Reference/media_handle_sideload
     *
     * @param string $url
     * @param string $video_id
     *
     * @return int|bool
     */
    private function create_from_thumbnail( $url, $video_id )
    {
        $tmp = download_url( $url );
        if( is_wp_error( $tmp ) ) {
            Log::error( new Exception( 'WP_Error:' . implode( ' | ', $tmp->get_error_messages() ) ) );
            return false;
        }
        $file_array = [];
        // Set variables for storage
        // fix file filename for query strings
        preg_match( '/[^\?]+\.(jpg|jpe|jpeg|gif|png)/i', $url, $matches );
        $file_array['name'] = isset( $matches[0] ) ? basename( $matches[0] ) : $video_id . '.jpg';
        $file_array['tmp_name'] = $tmp;
        // If error storing temporarily, unlink
        if ( is_wp_error( $tmp ) ) {
            @unlink( $file_array['tmp_name'] );
            $file_array['tmp_name'] = '';
        }
        // Store
        $id = media_handle_sideload( $file_array, 0 );
        // If error storing permanently, unlink
        if ( is_wp_error( $id ) ) {
            Log::error( new Exception( 'WP_Error:' . implode( ' | ', $tmp->get_error_messages() ) ) );
            @unlink( $file_array['tmp_name'] );
            return false;
        }
        return $id;
    }
    /**
     * Returns embed for youtube and vimeo videos.
     * @since 2.1.0
     * 
     * @hook send_to_editor
     *
     * @param string $html    Current HTML.
     * @param int    $post_id Attachment ID.
     *
     * @return string
     */
    public function send_to_editor( $html, $post_id )
    {
        $attachment = Attachment::find( $post_id );
        if ( in_array( $attachment->mime, apply_filters( 'post_gallery_video_mimes', ['video/youtube','video/vimeo'] ) ) )
            return apply_filters( 'post_gallery_video_to_editor', $attachment->embed, $attachment );
        if ( $attachment->mime === 'video/mp4' )
            return preg_replace( '/\]\[/', ' id="'.$attachment->ID.'" img="'.$attachment->video_thumb.'"][', $html );
        return $html;
    }
    /**
     * Returns the edit fields of the attachment, during upload manager.
     * @since 2.1.0
     * 
     * @hook attachment_fields_to_edit
     *
     * @param array  $fields Current.
     * @param object $post   Post.
     *
     * @return array
     */
    public function fields( $fields, $post )
    {
        $attachment = Attachment::find( $post->ID );
        if ( in_array( $attachment->mime, apply_filters( 'post_gallery_video_mimes', ['video/youtube','video/vimeo'] ) ) ) {
            $fields['video_provider'] = [
                'label' => __( 'Provider', 'simple-post-gallery' ),
                'input' => 'html',
                'html'  => apply_filters(
                            'post_gallery_video_mime_icon',
                            '<i class="fa fa-'.($attachment->mime === 'video/youtube' ? 'youtube' : 'vimeo').'" style="font-size: 40px" title="'.$attachment->video_provider.'"></i>',
                            $attachment
                        ),
            ];
            $fields['video_watch'] = [
                'label' => __( 'Watch', 'simple-post-gallery' ),
                'input' => 'html',
                'html'  => '<a href="'.$attachment->video_url.'" target="_blank">'.__( 'Click to watch', 'simple-post-gallery' ).'</a>',
            ];
            $fields['embed_size'] = [
                'label' => __( 'Embed', 'simple-post-gallery' ),
                'input' => 'html',
                'html'  => $this->view->get( 'media.video-embed-select', ['attachment' => &$attachment, 'post' => &$post]  ),
            ];
        }
        return $fields;
    }
    /**
     * Triggered when the media video is being edited.
     * @since 2.1.0
     * 
     * @hook attachment_fields_to_save
     *
     * @param object $post   Post.
     * @param array  $fields Fields to save.
     *
     * @return object
     */
    public function fields_save( $post, $fields )
    {
        if ( isset( $fields['embed_size'] ) ) {
            update_post_meta( $post['ID'], 'video_embed_size', $fields['embed_size'] );
        }
        return $post;
    }
    /**
     * Returns attachment metadata.
     * This is a small hack to add 'full' index if missing.
     * @since 2.3.12
     *
     * @hook wp_get_attachment_metadata
     * 
     * @param array $data
     * @param int   $attachment_id
     *
     * @return array
     */
    public function metadata( $data, $attachment_id )
    {
        $attachment = Attachment::find( $attachment_id );
        if ( $attachment->is_video && !array_key_exists( 'full', $data['sizes'] ) ) {
            $data['sizes']['full'] = [
                'file' => preg_replace( '/[\s\S]+\//', '', $data['file'] ),
                'width' => $data['width'],
                'height' => $data['height'],
                'mime-type' => strpos( $data['file'], '.jpg' ) ? 'image/jpeg' : 'image/png',
            ];
        }
        return $data;
    }
}