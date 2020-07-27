<!-- Default page format for pages that aren't the homepage and don't have their own php file -->

<?php
    get_header();

    while(have_posts()) {
        the_post();
        pageBanner();
?>

  <div class="container container--narrow page-section">

    <?php
        $parent_post_id = wp_get_post_parent_id(get_the_ID());
        if($parent_post_id) 
        { 
    ?>
            <div class="metabox metabox--position-up metabox--with-home-link">
                <p><a class="metabox__blog-home-link" href="<?php echo get_permalink($parent_post_id); ?>"><i class="fa fa-home" aria-hidden="true"></i> Back to <?php echo get_the_title($parent_post_id); ?></a> <span class="metabox__main"><?php the_title(); ?></span></p>
            </div>
    <?php 
        }
    ?>
 
    <?php
        $pages_array = get_pages(array(
            "child_of" => get_the_ID()
        ));

        if($parent_post_id or $pages_array) { ?>
            <div class="page-links">
            <h2 class="page-links__title"><a href="<?php echo get_permalink($parent_post_id) ?>"><?php echo get_the_title($parent_post_id); ?></a></h2>
            <ul class="min-list">
                <?php
                    if ($parent_post_id)
                    {
                        $find_children = $parent_post_id;
                    }
                    else
                    {
                        $find_children = get_the_ID();
                    }
                    wp_list_pages(array(
                        "title_li" => NULL,
                        "child_of" => $find_children,
                        //"sort_column" => "menu_order"     Uncomment if don't want alphabetical order
                    ));
                ?>
            </ul>
            </div>
    <?php } ?>
    
    <div class="generic-content">
        <?php the_content() ?>
    </div>

  </div>
  
  <br/><br/>

<?php }

    get_footer();
    
?>