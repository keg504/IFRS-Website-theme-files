<!-- Format of front/homepage of the website -->

<?php 

    get_header();

?>

<div class="page-banner">
  <div class="page-banner__bg-image" style="background-image: url(<?php echo get_theme_file_uri("/images/surgery-banner.jpg") ?>);"></div>
    <div class="page-banner__content container t-center c-white">
      <h2 class="headline headline--medium">Working to make surgery accessible worldwide</h2>
      <h3 class="headline headline--small">Register for membership today</h3>
      <a href="<?php echo esc_url(site_url( '/ifrs-registration')); ?>" class="btn btn--large btn--blue">Register</a>
    </div>
  </div>

  <div class="full-width-split group">
    <div class="full-width-split__one">
      <div class="full-width-split__inner">
        <h2 class="headline headline--small-plus t-center">Upcoming Events</h2>
        
        <?php
        // Query to find next two upcoming events
          $today = date("Ymd");
          $homepage_events = new WP_Query(array(
            "posts_per_page" => 2,
            "post_type" => "Event",
            "meta_key" => "event_date",
            "orderby" => "meta_value_num",
            "order" => "ASC",
            "meta_query" => array(
              array(
                "key" => "event_date",
                "compare" => ">=",
                "value" => $today,
                "type" => "numeric"
              )
            )
          ));
          
          // Display upcoming events based on query results and using template stored in subfolder of theme directory
          while($homepage_events->have_posts())
          {
            $homepage_events->the_post();
            get_template_part("template-parts/content", "event");
          }
        ?>
        
        <p class="t-center no-margin"><a href="<?php echo get_post_type_archive_link("event"); ?>" class="btn btn--blue">View All Events</a></p>

      </div>
    </div>
    <div class="full-width-split__two">
      <div class="full-width-split__inner">
        <h2 class="headline headline--small-plus t-center">From Our Blogs</h2>
        <?php
        
            // Query to find latest two blog posts and link to them
            $homepage_posts = new WP_Query(array(
                "posts_per_page" => 2,
            ));

            while ($homepage_posts->have_posts())
            {
                $homepage_posts->the_post(); ?>
                <div class="event-summary">
                    <a class="event-summary__date event-summary__date--beige t-center" href="<?php the_permalink(); ?>">
                        <span class="event-summary__month"><?php the_time("M"); ?></span>
                        <span class="event-summary__day"><?php the_time("d"); ?></span>  
                    </a>
                    <div class="event-summary__content">
                        <h5 class="event-summary__title headline headline--tiny"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h5>
                        <p><?php if (has_excerpt())
                        {
                          echo get_the_excerpt();
                        }
                        else
                        {
                          echo wp_trim_words(get_the_content(), 18);
                        }
                         ?><a href="<?php the_permalink(); ?>" class="nu c-white"> ...continue reading</a></p>
                    </div>
                </div>
            <?php } wp_reset_postdata();
        ?>
        
        <p class="t-center no-margin"><a href="<?php echo site_url("/blog"); ?>" class="btn btn--green">View All Blog Posts</a></p>
      </div>
    </div>
  </div>

  <?php

    // Query to find notices for slider on homepage. Not displayed if there aren't any
    $homepage_notices = new WP_Query(array(
            "posts_per_page" => -1,
            "post_type" => "front-notice",
            "meta_key" => "show_notice_until",
            "orderby" => "meta_value_num",
            "meta_query" => array(
              array(
                "key" => "show_notice_until",
                "compare" => ">=",
                "value" => $today,
                "type" => "numeric"
              )
            )
          ));

    if ($homepage_notices->have_posts())
    {
  ?>
      <div class="hero-slider">
        <div data-glide-el="track" class="glide__track">
          <div class="glide__slides">
            <?php
              while ($homepage_notices->have_posts())
              {
                $homepage_notices->the_post();
                get_template_part('template-parts/content', 'front-notice');
              }
            ?>
          </div>
          <div hidden style="background-color: #202020;" class="slider__bullets glide__bullets" data-glide-el="controls[nav]">
          </div>
        </div>
      </div>

<?php
      wp_reset_postdata();
    }
    echo "";
    get_footer();

?>