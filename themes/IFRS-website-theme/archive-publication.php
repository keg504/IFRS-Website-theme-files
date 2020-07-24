<!-- Archive page format for publication post type -->

<?php

  get_header();
  pageBanner(array(
    "title" => "Publications",
    "subtitle" => "Resources for rural surgeons worldwide"
  ))

?>

<div class="container container--narrow page-section">
  <?php
    while(have_posts())
    {
      the_post(); ?>
      <div class="post-item">
        <h2 class="headline headline--medium headline--post-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
        <div class="metabox">
          <p>Posted by <?php the_author_posts_link(); ?> on <?php the_time("n.j.y"); ?> in <?php echo get_the_category_list(", "); ?></p>
        </div>
        <p><?php echo get_the_excerpt(); ?></p>
        <div class="generic-content">
          <p><a class="btn btn--blue" href="<?php the_permalink(); ?>">Get File &raquo;</a></p>
        </div>
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