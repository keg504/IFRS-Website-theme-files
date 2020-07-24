<!-- Page format for a single publication post -->

<?php

get_header();

    while(have_posts()) {
        the_post();
    
?>

<div class="container container--narrow page-section">
    <h1 class="headline headline--medium"><?php echo the_title(); ?></h1>
    <br/><br/>
    <div class="metabox metabox--position-up metabox--with-home-link">
        <p><a class="metabox__blog-home-link" href="<?php echo esc_url(site_url("/publications")); ?>"><i class="fa fa-home" aria-hidden="true"></i> All Publications</a> <span class="metabox__main">Posted by <?php the_author_posts_link(); ?> on <?php the_time("n.j.y"); ?> in <?php echo get_the_category_list(", ") ?></span></p>
    </div>

    <div class="generic-content">
        <?php
            $publicationDocument = get_field("publication_document");
            
            if ($publicationDocument)
            {
                ?>
                <ul class="link-list min-list">
                    <li style="display:inline"><a href="<?php echo esc_url($publicationDocument['url']) ?>"><i class="fa fa-download" aria-hidden="true"></i></a>
                    <li style="display:inline">&nbsp;&nbsp;</li>
                    <li style="display:inline"><a href="<?php echo esc_url($publicationDocument['url']) ?>"><?php echo $publicationDocument['filename']; ?></a></li>
                </ul> 
                <?php
            }
            else
            {
                echo '<p>Error. Document not found</p>';
            }
        ?>
    </div>
    <?php

            // Find and display related members as authors of the post
            $relatedMembers = get_field("related_members");
            
            if ($relatedMembers)
            {
                echo "<hr class='section-break'>";
                echo '<h2 class="headline headline--small">Author(s)</h2>';
                foreach($relatedMembers as $member)
                { ?>
                    <ul class='link-list min-list'>
                        <li style="font-size: medium;">
                            <a href="<?php echo get_the_permalink( $member ) ?>"><?php echo get_the_title( $member ); ?></a>
                        </li>
                    </ul>
                <?php }
            }

            // Find related events related to the program
            $related_events = new WP_Query(array(
              "posts_per_page" => -1,
              "post_type" => "event",
              "meta_key" => "event_date",
              "orderby" => "meta_value_num",
              "order" => "ASC",
              "meta_query" => array(
                array(
                    "key" => "event_date",
                ),
                array(
                    "key" => "related_publication",
                    "compare" => "LIKE",
                    "value" => '"' . get_the_ID() . '"' // Needs to be double quotes within single quotes, NOT the other way around (Will not work otherwise)
                )
              )
            ));

            // Output related events, if any, below the relevant publication
            if ($related_events->post_count != 0)
            {
                echo '<hr class="section-break">';
                echo '<h1 class="headline headline--small">Related Events</h1>';
                
                while($related_events->have_posts())
                {
                    $related_events->the_post();
                    get_template_part("template-parts/content-event"); 
                }
            }

            wp_reset_postdata();
        ?>
</div>



<?php 
    
    }

    get_footer();

?>