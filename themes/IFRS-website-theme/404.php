<!-- Format for 404 page that is displayed if a website resource is not found -->

<?php
    get_header();
?>

  
<div class="container container--narrow page-section">
    <div class="generic-content">
        <h2 style="text-align: center;" class="headline headline--small">Error 404. Content not found. Sorry!</h2>
        <div style="text-align: center;">
            <a style="text-align: center;" class="btn btn--dark-orange" href="<?php echo esc_url(site_url('/')); ?>">Back to Homepage</a>
        </div>
    </div>
</div>
<br/><br/>
<?php

    get_footer();
    
?>