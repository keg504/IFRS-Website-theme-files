<!-- Page format for a single program post -->

<?php

get_header();

while(have_posts()) {
    the_post();
    pageBanner();
    
?>

    <div class="container container--narrow page-section">
        <div class="metabox metabox--position-up metabox--with-home-link">
            <p><a class="metabox__blog-home-link" href="<?php echo get_post_type_archive_link("program"); ?>"><i class="fa fa-home" aria-hidden="true"></i> Current Training Programs</a> <span class="metabox__main"><?php the_title(); ?></span></p>
        </div>
        <div class="generic-content"><?php the_field('main_body_content'); ?></div>

        <!-- Show the related publications for each program -->
        <?php

            $relatedPublications = get_field("related_publication");
            
            if ($relatedPublications)
            {
                echo "<hr class='section-break'>";
                echo "<h2 class='headline headline--medium'>Related Publication(s)</h2>";
                echo "<ul class='link-list min-list'>";
                foreach($relatedPublications as $publication)
                { ?>
                    <li><a href="<?php echo get_the_permalink( $publication ) ?>"><?php echo get_the_title( $publication ); ?></a></li>
                <?php }
                echo "</ul>";
            }

            // Find related events related to the program
            $today = date("Ymd");
            $related_events = new WP_Query(array(
                "posts_per_page" => -1,
                "post_type" => "event",
                "meta_key" => "event_date",
                "orderby" => "meta_value_num",
                "order" => "ASC",
                "meta_query" => array(
                  array(
                      "key" => "event_date",
                      "compare" => ">=",
                      "value" => $today,
                      "type" => "numeric"
                  ),
                  array(
                      "key" => "related_program",
                      "compare" => "LIKE",
                      "value" => '"' . get_the_ID() . '"' // Needs to be double quotes within single quotes, NOT the other way around (Will not work otherwise)
                  )
                )
              ));
  
              // Output related events, if any, below the relevant publication
              if ($related_events->post_count != 0)
              {
                  var_dump($related_events);
                  echo '<hr class="section-break">';
                  echo '<h2 class="headline headline--small">Upcoming related events</h2>';
                  
                  while($related_events->have_posts())
                  {
                      $related_events->the_post();
                      get_template_part("template-parts/content-event"); 
                  }
              }
  
              wp_reset_postdata();
            
        ?>
        

    </div>

    <br/><br/>

<?php }

    get_footer();

?>