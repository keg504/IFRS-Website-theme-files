<!-- Archive page format for member post type -->

<?php

  get_header();
  pageBanner(array(
    "title" => "A list of IFRS members",
    "subtitle" => "Contact information for IFRS members"
  ))

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

<br/><br/>

<?php

  get_footer();

 ?>