<!-- Archive page format for member post type -->

<?php

  get_header();
  pageBanner();

?>

<div class="container container--narrow page-section">
  <?php
    $leaderQuery = new WP_Query(array(
        'posts_per_page' => -1,
        'post_type' => 'member',
        'category_name' => 'leadership',
        'order_by' => 'category_description',
        'order' => 'ASC'
    ));

    while($leaderQuery->have_posts())
    {
        $leaderQuery->the_post();
        $postCategories = get_the_category();
         ?>
        <div class="post-item">
            <p class="headline headline--small"><a href="<?php echo esc_url(get_the_permalink($post)); ?>"><?php echo get_the_title($post); ?></a> - <?php
                foreach($postCategories as $postCategory)
                {
                    if ($postCategory->name !== "Leadership")
                    {
                        echo $postCategory->name;
                    }
                }
              ?></p>
        </div> <?php
    }
    wp_reset_query();
    echo paginate_links();
  ?>
</div>

<br/><br/>

<?php

  get_footer();

 ?>