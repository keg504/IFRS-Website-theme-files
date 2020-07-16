<!-- Archive page format for media post type -->

<?php

  get_header();

?>

<div class="container container--narrow page-section">
  <?php
    while(have_posts())
    {
      the_post(); ?>
      <div class="post-item">
        <p class="headline headline--small"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></p>
      </div>
  <?php
    }
    echo paginate_links();
  ?>
</div>

<?php

  get_footer();

 ?>