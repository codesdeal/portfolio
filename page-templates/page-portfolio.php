<?php
/*
Template Name: Portfolio Page
*/

get_header(); ?>

<div class="o-container">
    <div class="row">
        <div class="col-12">
            <div class="page-header text-center mb-5">
                <h1 class="page-title"><?php the_title(); ?></h1>
                <?php the_content(); ?>
            </div>
        </div>
    </div>
</div>

<?php get_template_part('template-parts/portfolio/grid'); ?>

<?php get_footer(); ?>