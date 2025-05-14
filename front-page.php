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

    <!-- about me section -->
    <?php get_template_part('template-parts/home/overview'); ?>
    <!-- ###about me section -->

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
</main>
<!-- ###main -->

<?php get_footer(); ?>