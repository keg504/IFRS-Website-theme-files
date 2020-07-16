<!-- Page format for a single member post -->

<?php

get_header();

while(have_posts())
{
    the_post();
    pageBanner();

?>
    

    <div class="container container--narrow page-section">
        <div class="metabox metabox--position-up metabox--with-home-link">
            <p><a class="metabox__blog-home-link" href="<?php echo get_post_type_archive_link("member"); ?>"><i class="fa fa-home" aria-hidden="true"></i> List of members</a> <span class="metabox__main"><?php the_title(); ?></span></p>
        </div>
        <div class="generic-content">
            <div class="row group">
                <div class="one-third">
                    <?php
                        if (has_post_thumbnail())
                        {
                            the_post_thumbnail("memberPortrait");
                        }
                        else
                        { ?>
                            <img src="<?php bloginfo('template_directory'); ?>/images/generic_person.png"/>
                        <?php }
                    ?>
                </div>
                <div class="two-thirds">                        
                    <?php the_content(); ?>
                </div>
            </div>    
        </div>
    </div>

<?php }

get_footer();

?>