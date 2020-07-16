<?php

namespace PostGallery\Controllers;

use WPMVC\Request;
use WPMVC\MVC\Controller;
/**
 * Notice hooks.
 * WordPress MVC Controller.
 *
 * @author Alejandro Mostajo <http://about.me/amostajo>
 * @copyright 10Quality
 * @package simple-post-gallery
 * @version 2.2.0
 */
class NoticeController extends Controller
{
    /**
     * Checks for dismissed notices.
     * @since 2.1.2
     * 
     * @hook admin_init
     */
    public function check_dismissed()
    {
        $dismissed = Request::input( 'post-gallery-dismissed' );
        if ( $dismissed )
            update_option( 'postgallery_'.$dismissed.'_show', 0, true );
        unset( $dismissed );
    }
    /**
     * Display important admin notices.
     * @since 2.1.2
     * 
     * @hook admin_notices
     */
    public function show()
    {
        /*
        if ( get_option( 'postgallery_notice_0_show', true ) ) {
            $this->view->show( 'plugins.post-gallery.admin.notices.lightbox-notice' );
        }
        */
    }
}