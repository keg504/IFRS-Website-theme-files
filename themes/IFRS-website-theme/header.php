<!-- Format for header of all webpages of site, called by the wordpress get_header() function -->

<!DOCTYPE html>

<html <?php language_attributes(); ?>>
    <head>
        <meta charset="<?php bloginfo("charset"); ?>">
        <meta name="viewport" content="width=device-width, intial-scale=1">
        <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
        <?php wp_head(); ?>
    </head>
    <body <?php body_class(); ?>>
    <div class="wrapper">
        <header class="site-header">
            <div class="container">
                <h1 class="school-logo-text float-left"><a href="<?php echo site_url() ?>"><strong>International Federation of Rural Surgeons</strong></a></h1>
                <a href="<?php echo esc_url(site_url('/search')); ?>" class="js-search-trigger site-header__search-trigger"><i class="fa fa-search" aria-hidden="true"></i></a>
                <i class="site-header__menu-trigger fa fa-bars" aria-hidden="true"></i>
                <div class="site-header__menu group">
                    <nav class="main-navigation">
                        <?php
                            wp_nav_menu(array(
                                "theme_location" => "header_menu_location"
                            ));
                        ?>

                        <!--<ul>
                            <li <?php if(is_page("about-us") or wp_get_post_parent_id(0) == 11) echo 'class="current-menu-item"'; ?>><a href="<?php echo site_url("/about-us") ?>">About Us</a></li>
                            <li <?php if(get_post_type() == "event" OR is_page("past-events")) echo "class='current-menu-item'"?>><a href="<?php echo get_post_type_archive_link('event')?>">Events</a></li>
                            <li <?php if(get_post_type() == "program") echo 'class="current-menu-item"' ?>><a href="<?php echo get_post_type_archive_link( 'program' )?>">Training Programs</a></li>
                            <li <?php if(get_post_type() == "campus") echo 'class="current-menu-item"' ?>><a href="<?php echo get_post_type_archive_link("campus") ?>">Publications</a></li>
                            <li><a href="#">Contact Us</a></li>
                            <li <?php if (get_post_type() == "post") echo 'class="current-menu-item"'; ?>><a href="#">Blog</a></li>
                        </ul>-->
                    </nav>
                    <div class="site-header__util">
                        <?php if(is_user_logged_in())
                        { ?>
                            <a href="<?php echo wp_logout_url(); ?>" class="btn btn--small  btn--green float-left btn--with-photo">
                                <span class="site-header__avatar"><?php echo get_avatar(get_current_user_id(), 60); ?></span>
                                <span class="btn__text">Log Out</span>
                            </a>
                        <?php
                        }
                        else
                        { ?>
                            <a href="<?php echo wp_login_url(); ?>" class="btn btn--small btn--blue float-left push-right">Login</a>
                            <a href="<?php echo wp_registration_url(); ?>" class="btn btn--small  btn--green float-left">Sign Up</a>
                        <?php }
                        ?>
                        <a href="<?php echo esc_url(site_url('/search')); ?>" class="search-trigger js-search-trigger"><i class="fa fa-search" aria-hidden="true"></i></a>
                    </div>
                </div>
            </div>
        </header>
        <div class="content">