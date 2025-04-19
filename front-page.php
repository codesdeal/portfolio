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

    <!-- cp block -->
    <?php get_template_part('template-parts/home/links-after-banner'); ?>
    <!-- ###cp block -->

    <!-- home features section -->
    <?php get_template_part('template-parts/home/features'); ?>
    <!-- ###home features section -->

    <!-- testimonials section -->
    <?php get_template_part('template-parts/home/testimonials'); ?>
    <!-- ###testimonials section -->

    <!-- home features section -->
    <?php get_template_part('template-parts/portfolio/grid'); ?>
    <!-- ###home features section -->

    <!-- home blog section -->
    <?php get_template_part('template-parts/home/blog'); ?>
    <!-- ###home blog section -->

</main>
<!-- ###main section -->
<?php get_footer(); ?>