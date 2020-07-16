<?php

    // Create custom REST API addition for custom search on non standard post types 
    add_action( 'rest_api_init', 'websiteRegisterSearch');

    // Create new REST API route for search results
    function websiteRegisterSearch()
    {
        register_rest_route( 'IFRSwebsite/v1', 'search', array(
            'methods' => WP_REST_SERVER::READABLE,
            'callback' => 'websiteSearchResults'
        ));
    }

    // Function that returns data using the newly created route
    function websiteSearchResults($search_request)
    {
        $searchResults = new WP_Query(array(
            'post_type' => array('post', 'page', 'member', 'event', 'publication', 'program'),  // Code to search each post type named in array
            's' => sanitize_text_field($search_request['term'])                                 // Sanitize input to prevent malicious code input
        ));

        // Array to clssify search results by post type
        $results = array(
            'generalInfo' => array(),
            'members' => array(),
            'programs' => array(),
            'events' => array(),
            'publications' => array()
        );

        // Push each search result into array, adding fields to JSON data as relevant to post type
        while($searchResults->have_posts())
        {
            $searchResults->the_post();
            if (get_post_type() == 'post' OR get_post_type() == 'page')
            {
                array_push($results['generalInfo'], array(
                    'title' => get_the_title(),
                    'permalink' => get_the_permalink(),
                    'post_type' => get_post_type(),
                    'authorName' => get_the_author()
                ));
            }
            
            if (get_post_type() == 'member')
            {
                array_push($results['members'], array(
                    'title' => get_the_title(),
                    'permalink' => get_the_permalink(),
                    'image' => get_the_post_thumbnail_url(0, 'professorLandscape')
                ));
            }

            if (get_post_type() == 'event')
            {
                $eventDate = new DateTime(get_field("event_date"));
                $description = null;
                if (has_excerpt())
                {
                    $description = get_the_excerpt();
                }
                else
                {
                    $description = wp_trim_words(get_the_content(), 18);
                }

                array_push($results['events'], array(
                    'title' => get_the_title(),
                    'permalink' => get_the_permalink(),
                    'month' => $eventDate->format('M'),
                    'day' => $eventDate->format('d'),
                    'description' => $description
                ));
            }

            if (get_post_type() == 'program')
            {
                array_push($results['programs'], array(
                    'title' => get_the_title(),
                    'permalink' => get_the_permalink(),
                    'id' => get_the_ID()
                ));
            }
            
            if (get_post_type() == 'publication')
            {
                array_push($results['publications'], array(
                    'title' => get_the_title(),
                    'permalink' => get_the_permalink()
                ));
            }
        }

        // Prevent wrong results being displayed for queries that should not return results
        if ($results['programs'])
        {

            /*
            *    Make sure to display multiple programs with similar names using OR condition to override default AND,
            *    and store results of search in array
            */
            $programsMetaQuery = array('relation' => 'OR');

            // Loop through the results of the metaquery and push them into the array of program results
            foreach($results['programs'] as $result)
            {
                array_push($programsMetaQuery, array(
                    'key' => 'related_program',
                    'compare' => 'LIKE',
                    'value' => '"' . $result['id'] . '"'
                ));
            }

            // Custom query to find relationships between posts to display them in search
            $programRelationshipQuery = new WP_Query(array(
                'post_type' => array('member', 'event'),
                'meta_query' => $programsMetaQuery
            ));

            // Push results of relationship based query into results array
            while($programRelationshipQuery->have_posts())
            {
                $programRelationshipQuery->the_post();

                if (get_post_type() == 'member')
                {
                    array_push($results['members'], array(
                        'title' => get_the_title(),
                        'permalink' => get_the_permalink(),
                        'image' => get_the_post_thumbnail_url(0, 'professorLandscape')
                    ));
                }

                if (get_post_type() == 'event')
                {
                    $eventDate = new DateTime(get_field("event_date"));
                    $description = null;
                    if (has_excerpt())
                    {
                        $description = get_the_excerpt();
                    }
                    else
                    {
                        $description = wp_trim_words(get_the_content(), 18);
                    }

                    array_push($results['events'], array(
                        'title' => get_the_title(),
                        'permalink' => get_the_permalink(),
                        'month' => $eventDate->format('M'),
                        'day' => $eventDate->format('d'),
                        'description' => $description
                    ));
                }
            }

            // Prevent return of duplicate results
            $results['members'] = array_values(array_unique($results['members'], SORT_REGULAR));
            $results['events'] = array_values(array_unique($results['events'], SORT_REGULAR));
            $results['events'] = array_values(array_unique($results['publications'], SORT_REGULAR));
            $results['events'] = array_values(array_unique($results['posts'], SORT_REGULAR));

        }
        
        return $results;
    }