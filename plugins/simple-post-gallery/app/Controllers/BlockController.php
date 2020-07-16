<?php

namespace PostGallery\Controllers;

use WPMVC\MVC\Controller;

/**
 * Handles gutenberg related hooks.
 * WordPress MVC Controller.
 *
 * @author Cami Mostajo
 * @copyright 10Quality <http://www.10quality.com>
 * @package simple-post-gallery
 * @version 2.3.8
 */
class BlockController extends Controller
{
    /**
     * Registers gutenbergs blocks.
     * @since 2.3.0
     * 
     * @hook init
     * 
     * @link https://www.youtube.com/watch?v=Qsf4YODc-cQ
     */
    public function register()
    {
        // --- POST GALLERY BLOCK
        wp_register_script(
            'post-gallery-block-editor',
            assets_url( 'blocks/gallery/editor.js', __DIR__ ),
            [ 'wp-blocks', 'wp-element', 'wp-i18n', 'wp-api-fetch' ],
            post_gallery()->config->get( 'version' )
        );
        wp_register_style(
            'post-gallery-block-editor',
            assets_url( 'blocks/gallery/editor.css', __DIR__ ),
            [ 'wp-edit-blocks' ],
            post_gallery()->config->get( 'version' )
        );
        register_block_type( 'simple-post-gallery/gallery', [
            'editor_script' => 'post-gallery-block-editor',
            'editor_style'  => 'post-gallery-block-editor',
        ] );
    }
}