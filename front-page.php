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

    <div id="lottie-animation" style="width: 300px; height: 300px;"></div>


</main>
<!-- ###main section -->
 <script>
    document.addEventListener("DOMContentLoaded", function () {
        lottie.loadAnimation({
            container: document.getElementById('lottie-animation'),
            renderer: 'svg',
            loop: true,
            autoplay: true,
            path: '<?php echo get_template_directory_uri(); ?>/dist/assets/animations/kidsstuf-station.json'
        });
    });
</script>
<?php get_footer(); ?>