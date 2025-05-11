<?php

/**
 * Template Name: Front Page
 * 
 * The template for displaying the front page.
 *
 * @package _themename
 */

get_header(); ?>
<main role="main">
    <!-- hero section -->
    <?php get_template_part('template-parts/home/hero'); ?>
    <!-- ###hero section -->

    <!-- featured clients section -->
    <?php get_template_part('template-parts/home/company-logo'); ?>
    <!-- ###featured clients section -->

    <!-- testimonials section -->
    <?php get_template_part('template-parts/home/testimonials'); ?>
    <!-- ###testimonials section -->

    <!-- home portfolio section -->
    <?php get_template_part('template-parts/portfolio/grid'); ?>
    <!-- ###home portfolio section -->

    <!-- home blog section -->
    <?php get_template_part('template-parts/home/blog'); ?>
    <!-- ###home blog section -->

    <div id="lottie-animation"></div>



</main>
<!-- ###main section -->
    <?php
        $lottie_path = content_url('/uploads/2025/05/data.json');
    ?>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            if (typeof lottie !== 'undefined') {
                lottie.loadAnimation({
                    container: document.getElementById('lottie-animation'),
                    renderer: 'svg',
                    loop: true,
                    autoplay: true,
                    path: "<?php echo esc_url($lottie_path); ?>"
                });
            } else {
                console.error("Lottie library is not loaded.");
            }
        });
    </script>



<?php get_footer(); ?>