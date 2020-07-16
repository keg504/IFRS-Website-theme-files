<!-- Format for footer that is loaded on every webpage for the website.
 Please do not deploy on website without proper testing on a test server.
 Called using wordpress get_footer() function -->

            <div class="push"></div>
        </div>
        <footer class="site-footer">

            <div class="site-footer__inner container container--narrow">

                <div class="group">

                    <div class="site-footer__col-one">
                        <h1 class="school-logo-text school-logo-text--alt-color"><a href="<?php echo site_url() ?>" ><strong>International Federation of Rural Surgeons</strong></a></h1>
                    </div>

                    <div class="site-footer__col-two-three-group">
                        <div class="site-footer__col-two">
                            <h3 class="headline headline--small">People</h3>
                            <nav class="nav-list">
                                <?php
                                    wp_nav_menu(array(
                                        "theme_location" => "footer_menu_location_one"
                                    ));
                                ?>
                                
                                <!-- HTML based footer menu. Not used for this website. WordPress version is used instead -->
                                <!--<ul>
                                    <li><a href="<?php echo site_url("/about-us") ?>">About Us</a></li>
                                    <li><a href="#">Programs</a></li>
                                    <li><a href="#">Events</a></li>
                                    <li><a href="#">Publications</a></li>
                                </ul>-->
                            </nav>
                        </div>

                        <div class="site-footer__col-three">
                            <h3 class="headline headline--small">Site Information</h3>
                            <nav class="nav-list">
                                <?php
                                    wp_nav_menu(array(
                                        "theme_location" => "footer_menu_location_two"
                                    ));
                                ?>

                                <!-- HTML based footer menu. Not used for this website. WordPress version is used instead -->
                                <!--<ul>
                                    <li><a href="#">Legal</a></li>
                                    <li><a href="<?php echo site_url("/privacy-policy") ?>">Privacy</a></li>
                                    <li><a href="#">Careers</a></li>
                                </ul>-->
                            </nav>
                        </div>
                    </div>

                    <div class="site-footer__col-four">
                        <h3 class="headline headline--small">Connect Online</h3>
                        <nav>
                            <ul class="min-list social-icons-list group">
                                <li><a href="#" class="social-color-twitter"><i class="fa fa-twitter" aria-hidden="true"></i></a></li>
                                <li><a href="#" class="social-color-youtube"><i class="fa fa-youtube" aria-hidden="true"></i></a></li>
                                <li><a href="#" class="social-color-linkedin"><i class="fa fa-linkedin" aria-hidden="true"></i></a></li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
            <div class="footer--spacing"></div>
        </footer>
        </div>
        
        <?php wp_footer(); ?>
    </body>
</html>