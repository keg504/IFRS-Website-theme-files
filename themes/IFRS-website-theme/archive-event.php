<!-- Archive page format for event post type -->

<?php

  get_header();
  pageBanner(array(
    "title" => "Events",
    "subtitle" => "Current and upcoming events of the IFRS"
  ))

?>

<div class="container container--narrow page-section">
  <?php
    while(have_posts())
    {
      the_post();
      get_template_part( "template-parts/content", "event" );
    }
    echo paginate_links();
  ?>

  <hr class="section-break">
  <p class="headline headline--small-plus"><a href="<?php echo site_url('/past-events')?>">Past events archive here</a></p>

</div>
<br/><br/>
<?php

  get_footer();

 ?>