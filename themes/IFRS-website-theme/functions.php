<?php

    // Add file to keep code here organised
    require get_theme_file_path('/includes/search-route.php');

    // Function to add custom fields to REST API
    function website_custom_rest()
    {
        register_rest_field( 'post', 'authorName', array(
            'get_callback' => function() {return get_the_author();}
        ));
    }

    // Customising tht REST API to add more data to JSON data
    add_action('rest_api_init', 'website_custom_rest');

    // Function to load page banner data for each page. Includes fallback defaults
    function pageBanner($pageBannerData = NULL)
    {
        // Title field check and fallback
        if(!$pageBannerData["title"])
        {
            $pageBannerData["title"] = get_the_title();
        }

        // Subtitle field check and fallback
        if (!$pageBannerData["subtitle"])
        {
            $pageBannerData["subtitle"] = get_field("page_banner_subtitle");
        }

        // Photo field check and fallback
        if (!$pageBannerData["photo"])
        {
            if((get_queried_object_id() != 0) && get_field("page_banner_background_image"))
            {
                $pageBannerData["photo"] = get_field("page_banner_background_image")["sizes"]["pageBanner"];
            }
            else
            {
                $pageBannerData["photo"] = get_theme_file_uri("images/IFRS-banner.png");
            }
        }
?>

<!-- HTML layout for page banner -->
<div class="page-banner">
    <div class="page-banner__bg-image" style="background-image: url(<?php echo $pageBannerData["photo"]; ?>);"></div>
    <div class="page-banner__content container container--narrow">
        <h1 class="page-banner__title"><?php echo $pageBannerData["title"]; ?></h1>
        <div class="page-banner__intro">
            <p><?php echo $pageBannerData["subtitle"]; ?></p>
        </div>
    </div>  
</div>

<?php
    }

    // Function to load CSS stylesheets, script files and fonts
    function website_files()
    {
        wp_enqueue_style('custom-google-fonts', '//fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i');
        wp_enqueue_style('font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');

        if (strstr($_SERVER['SERVER_NAME'], 'international-federation-of-rural-surgeons.local')) {
            wp_enqueue_script('main-scripts', 'http://localhost:3000/bundled.js', NULL, '1.0', true);
          } else {
            wp_enqueue_script('our-vendors-js', get_theme_file_uri('/bundled-assets/vendors~scripts.9678b4003190d41dd438.js'), NULL, '1.0', true);
            wp_enqueue_script('main-scripts', get_theme_file_uri('/bundled-assets/scripts.f9587ffc172649173ad3.js'), NULL, '1.0', true);
            wp_enqueue_style('our-main-styles', get_theme_file_uri('/bundled-assets/styles.f9587ffc172649173ad3.css'));
          }

        wp_localize_script('main-scripts', 'websiteData', array(    // Generalise URL so that it is not hard coded to root folder or dev directory
            'root_url' => get_site_url()
        ));
    }

    add_action("wp_enqueue_scripts", "website_files");

    // Function to enable website features for theme that are not turned on by default
    function website_features()
    {
        // Add support for navigation menus and define their locations on the webpage
        register_nav_menu("header_menu_location", "Header Menu Location");
        register_nav_menu("footer_menu_location_one", "Footer Menu Location One");
        register_nav_menu("footer_menu_location_two", "Footer Menu Location Two");

        // Add support for titles and thumbnails for posts in theme
        add_theme_support("title-tag");
        add_theme_support("post-thumbnails");

        // Create new image sizes for use in different locations
        add_image_size('memberLandscape', 400, 260, true);
        add_image_size('memberPortrait', 480, 650, true);
        add_image_size( 'pageBanner', 1500, 350, true);
        add_image_size( 'noticeSlide', 1000, 600, true);
    }

    add_action("after_setup_theme", "website_features");

    // Ajust queries for archive pages of different types to organise how they are displayed
    function website_adjust_queries($query)
    {
        // Show all programs on the archive page
        if (!is_admin() AND is_post_type_archive("program") AND $query->is_main_query())
        {
            $query->set("orderby", "title");
            $query->set("order", "ASC");
            $query->set("posts_per_page", -1);
        }

        // Show pins for all campuses on map
        if (!is_admin() AND is_post_type_archive("campus") AND $query->is_main_query())
        {
            $query->set("posts_per_page", -1);
        }

        // Only show events that are currently happening or are in the future on the archive page
        if (!is_admin() AND is_post_type_archive("event") AND $query->is_main_query())
        {
            $today = date("Ymd");
            $query->set("meta_key", "event_date");
            $query->set("orderby", "meta_value_num");
            $query->set("order", "ASC");
            $query->set("meta_query", array(
                array(
                  "key" => "event_date",
                  "compare" => ">=",
                  "value" => $today,
                  "type" => "numeric"
                )
                ));
        }
    }

    add_action("pre_get_posts", "website_adjust_queries");

    // Redirect subscriber accounts out of admin and onto homepage
    add_action('admin_init', 'redirectSubsToFrontend');;

    function redirectSubsToFrontend()
    {
        $currentUser = wp_get_current_user();
        if (count($currentUser->roles) == 1 AND $currentUser->roles[0] == 'subscriber')
        {
            wp_redirect(site_url('/'));
            exit;
        }
    }

    // Remove admin bar for subscribers
    add_action('wp_loaded', 'noSubsAdminBar');

    function noSubsAdminBar()
    {
        $currentUser = wp_get_current_user();
        if (count($currentUser->roles) == 1 AND $currentUser->roles[0] == 'subscriber')
        {
            show_admin_bar(false);
        }
    }

    // Customise Login Screen
    add_filter( 'login_headerurl', 'siteHeaderUrl');

    function siteHeaderUrl()
    {
        return esc_url(site_url('/'));
    }

    add_action('login_enqueue_scripts', 'siteLoginCSS');

    function siteLoginCSS()
    {
        wp_enqueue_style('our-main-styles', get_theme_file_uri('/bundled-assets/styles.f9587ffc172649173ad3.css'));
        wp_enqueue_style('custom-google-fonts', '//fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i');
    }

    add_filter('login_headertitle', 'siteLoginTitle');

    function siteLoginTitle()
    {
        return get_bloginfo('name');
    }

    /**
     * Filter the upload size limit for non-administrators.
     *
     * @param string $size Upload size limit (in bytes).
     * @return int (maybe) Filtered size limit.
     */

    function filter_site_upload_size_limit( $size ) {
        // Set the upload size limit to 60 MB for users lacking the 'manage_options' capability.
        if ( ! current_user_can( 'manage_options' ) ) {
            // 50 MB.
            $size = 50 * 1024 * 1024;
        }
        return $size;
    }

    add_filter( 'upload_size_limit', 'filter_site_upload_size_limit', 20 );

    add_filter( 'ai1wm_exclude_content_from_export', 'ignoreCertainFiles');

    function ignoreCertainFiles($exclude_filters)
    {
        $exclude_filters[] = 'themes/IFRS-website-theme/node_modules';
        return $exclude_filters;
    }
?>
