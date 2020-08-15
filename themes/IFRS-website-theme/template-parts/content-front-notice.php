<?php
    $relatedPosts = get_field("related_post");
    $noticeImage = get_field("notice_image")['sizes']['noticeSlide'];
    //echo get_the_permalink($relatedPost);
?>

<div class="hero-slider__slide" style="background-image: url(<?php echo $noticeImage; ?>);">
    <div class="hero-slider__interior container">
        <div class="hero-slider__overlay">
            <h2 class="headline headline--medium t-center"><?php sanitize_text_field(the_field('notice_title')); ?></h2>
            <p class="t-center"><?php sanitize_text_field(the_field('notice_subtitle')); ?></p>
            <?php 
            foreach($relatedPosts as $relatedPost)
            { ?>
                <p class="t-center no-margin"><a href="<?php echo get_the_permalink($relatedPost); ?>" class="btn btn--blue">Learn more</a></p>
            <?php
            }
            ?>
        </div>
    </div>
    <div class="push"></div>
</div>

<!--( ! ) Warning: Illegal string offset 'url' in G:\Users\Kevin\Local Sites\international-federation-of-rural-surgeons\app\public\wp-content\themes\IFRS-website-theme\template-parts\content-front-notice.php on line 7 Call Stack #TimeMemoryFunctionLocation 10.0001416216{main}( )...\index.php:0 20.0005416488require( 'G:\Users\Kevin\Local Sites\international-federation-of-rural-surgeons\app\public\wp-blog-header.php' )...\index.php:17 30.14024137720require_once( 'G:\Users\Kevin\Local Sites\international-federation-of-rural-surgeons\app\public\wp-includes\template-loader.php' )...\wp-blog-header.php:19 40.14294155760include( 'G:\Users\Kevin\Local Sites\international-federation-of-rural-surgeons\app\public\wp-content\themes\IFRS-website-theme\front-page.php' )...\template-loader.php:106 50.20114462304get_template_part( )...\front-page.php:112 60.20124462800locate_template( )...\general-template.php:168 70.20124462992load_template( )...\template.php:672 80.20154467096require( 'G:\Users\Kevin\Local Sites\international-federation-of-rural-surgeons\app\public\wp-content\themes\IFRS-website-theme\template-parts\content-front-notice.php' )...\template.php:725 h);">-->
