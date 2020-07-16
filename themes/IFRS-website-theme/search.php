<!-- Search results page format if JavaScript has been disabled by the user or is not loaded on the webpage.
Is not loaded if JavaScript has loaded and overlay works -->

<?php

  get_header();
  pageBanner(array(
    "title" => "Search Results",
    "subtitle" => "You searched for &ldquo;" . esc_html(get_search_query(false)) . "&ldquo; "
  ))

?>

<div class="container container--narrow page-section">
  <?php

    if(have_posts())
    {
        while(have_posts())
        {
        the_post();
      
        get_template_part('template-parts/content', get_post_type( ));
        }

        echo paginate_links();
    } 
    else
    {
        echo '<h2 class="headline headline--small-plus">No results match that search.</h2>';
    }

    get_search_form();


?>
</div>

<?php

  get_footer();

 ?>