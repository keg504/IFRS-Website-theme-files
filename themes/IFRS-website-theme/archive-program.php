<!-- Archive page format for program post type -->

<?php

  get_header();
  pageBanner(array(
    "title" => "Training Programs",
    "subtitle" => "Currently running certificate training programs"
  ))

?>

<div class="container container--narrow page-section">
    <ul class="link-list min-list">
        <?php
            while(have_posts())
            {
                the_post(); ?>
                <li><a href="<? the_permalink()?>"><?php the_title() ?></a></li>
        <?php
            }
            echo paginate_links();
        ?>
    </ul>
</div>

<?php

  get_footer();

?>