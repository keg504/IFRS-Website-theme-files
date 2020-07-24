<?php

    // Function to create new custom post types for the website
    function IFRS_post_types()
    {
        // Event Post type
        register_post_type("event", array(
            'capability_type' => 'event',                                           // Setting for user role permissions
            'map_meta_cap' => true,                                                 // Shown in admin console for user permission
            "supports" => array("title", "editor", "excerpt"),                      // Default fields supported
            "rewrite" => array("slug" => "events"),                                 // Permalink structure for url
            "has_archive" => true,                                                  // Does it have an archive page that is publically available
            "public" => true,                                                       // Are posts of this type viewable by anyone that visits the site
            "labels" => array(                                                      // How it is referred to the dashboard for the admin
                "name" => "Events",
                "add_new_item" => "Add New Event",
                "edit_item" => "Edit Event",
                "all_items" => "Events",
                "singular_name" =>"Event"
            ),
            "menu_icon" => "dashicons-calendar-alt"                                 // What icon is used to represent it in the admin dashboard
        ));

        // Publication Post type
        register_post_type("publication", array(
            "supports" => array("title"),
            "rewrite" => array("slug" => "publications"),
            "has_archive" => true,
            "public" => true,
            "taxonomies" => array("category"),
            "labels" => array(
                "name" => "Publications",
                "add_new_item" => "Add New Publication",
                "edit_item" => "Edit Publication",
                "all_items" => "Publications",
                "singular_name" =>"Publication"
            ),
            "menu_icon" => "dashicons-media-document"
        ));

        // Training Program Post type
        register_post_type("program", array(
            "supports" => array("title"),
            "rewrite" => array("slug" => "programs"),
            "has_archive" => true,
            "public" => true,
            "labels" => array(
                "name" => "Programs",
                "add_new_item" => "Add New Program",
                "edit_item" => "Edit Program",
                "all_items" => "All Programs",
                "singular_name" =>"Program"
            ),
            "menu_icon" => "dashicons-feedback"
        ));

        // Member Post type
        register_post_type("member", array(
            "show_in_rest" => true,
            "supports" => array("title", "editor", "thumbnail"),
            "rewrite" => array("slug" => "members"),
            "has_archive" => true,
            "public" => true,
            "taxonomies" => array("category"),
            "labels" => array(
                "name" => "Members",
                "add_new_item" => "Add New Member",
                "edit_item" => "Edit Member",
                "all_items" => "All Members",
                "singular_name" =>"Member"
            ),
            "menu_icon" => "dashicons-id"
        ));

        // Media Post type
        register_post_type("media", array(
            "supports" => array("title", "editor", "thumbnail"),
            "rewrite" => array("slug" => "media"),
            "has_archive" => true,
            "public" => true,
            "labels" => array(
                "name" => "Media",
                "add_new_item" => "Add New Media",
                "edit_item" => "Edit Media",
                "all_items" => "All Media",
                "singular_name" =>"Media"
         ),
           "menu_icon" => "dashicons-images-alt"
        ));

        // Front notice post type for slideshow on homepage
        register_post_type("front-notice", array(
            "supports" => array('title'),
            "public" => true,
            "labels" => array(
                "name" => "Front Notices",
                "add_new_item" => "Add New Front Notice",
                "edit_item" => "Edit Front Notice",
                "all_items" => "All Front Notices",
                "singular_name" =>"Front Notice"
            ),
            "menu_icon" => "dashicons-pressthis"
        ));
    }

    add_action("init", "IFRS_post_types");

?>