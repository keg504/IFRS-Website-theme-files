<!-- Page format for a single event post -->

<?php

get_header();

while(have_posts()) {
    the_post();
    pageBanner();

?>

    <div class="container container--narrow page-section">
        <div class="metabox metabox--position-up metabox--with-home-link">
            <p><a class="metabox__blog-home-link" href="<?php echo get_post_type_archive_link("event"); ?>"><i class="fa fa-home" aria-hidden="true"></i> Events</a> <span class="metabox__main"><?php the_title(); ?></span></p>
        </div>
        <div class="generic-content"><?php the_content(); ?></div>

        <?php

            // Find related publications related to the event
            $relatedPublications = get_field("related_publication");
            
            if ($relatedPublications)
            {
                echo "<hr class='section-break'>";
                echo "<h2 class='headline headline--small'>Related Publication(s)</h2>";
                echo "<ul class='link-list min-list'>";
                foreach($relatedPublications as $publication)
                { ?>
                    <li><a href="<?php echo get_the_permalink($publication) ?>"><?php echo get_the_title( $publication ); ?></a></li>
                <?php }
                echo "</ul>";
            }

            // Find programs related to the event
            $relatedPrograms = get_field("related_program");
            
            if ($relatedPrograms)
            {
                echo "<hr class='section-break'>";
                echo "<h2 class='headline headline--small'>Related Program(s)</h2>";
                echo "<ul class='link-list min-list'>";
                foreach($relatedPrograms as $program)
                { ?>
                    <li><a href="<?php echo get_the_permalink($program) ?>"><?php echo get_the_title( $program ); ?></a></li>
                <?php }
                echo "</ul>";
            }


        ?>
    </div>

    <br/><br/>

<?php }

get_footer();

?>