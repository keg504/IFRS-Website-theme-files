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
            
        ?>
        

    </div>

<?php }

    get_footer();

?>