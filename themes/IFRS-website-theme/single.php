
<!-- Default page format for a single post of any type if custom PHP page has not been created for it -->

<?php

    get_header();

    while(have_posts()) {
        the_post();
        pageBanner();
        
?>

<div class="container container--narrow page-section">
    <div class="metabox metabox--position-up metabox--with-home-link">
        <p><a class="metabox__blog-home-link" href="<?php echo site_url("/blog"); ?>"><i class="fa fa-home" aria-hidden="true"></i> Blog Home</a> <span class="metabox__main">Posted by <?php the_author_posts_link(); ?> on <?php the_time("n.j.y"); ?> in <?php echo get_the_category_list(", ") ?></span></p>
    </div>

    <div class="generic-content"><?php the_content(); ?></div>

        <?php

            // Find and link the member of the IFRS that wrote this blog post
            $relatedMembers = get_field("related_members");
            
            if ($relatedMembers)
            {
                echo "<hr class='section-break'>";
                echo "<h2 class='headline headline--small'>Author</h2>";
                foreach($relatedMembers as $member)
                { ?>
                    <ul class='link-list min-list'>
                        <li>
                            <a class="headline" href="<?php echo get_the_permalink( $member ) ?>"><?php echo get_the_title( $member ); ?></a>
                        </li>
                    </ul>
                <?php }
            }

            // Find and link the publications connected to this blog post
            $relatedPublications = get_field("related_publication");
            
            if ($relatedPublications)
            {
                echo "<hr class='section-break'>";
                echo "<h2 class='headline headline--small'>Related Publication</h2>";
                foreach($relatedPublications as $publication)
                { ?>
                    <ul class='link-list min-list'>
                        <li>
                            <a class="headline" href="<?php echo get_the_permalink($publication) ?>"><?php echo get_the_title( $publication ); ?></a>
                        </li>
                    </ul>
                <?php }
            }
            ?>
</div>
            

<?php }

    get_footer();
    
?>