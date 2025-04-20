<?php
/**
 * Template part for displaying single portfolio items
 * @package _themename
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('portfolio-single'); ?>>
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <?php if (has_post_thumbnail()) : ?>
                    <div class="featured-image mb-4">
                        <?php the_post_thumbnail('full', ['class' => 'img-fluid']); ?>
                    </div>
                <?php endif; ?>
            </div>
            <div class="col-lg-4">
                <div class="project-info">
                    <h1 class="project-title"><?php the_title(); ?></h1>
                    
                    <?php 
                    $project_url = get_post_meta(get_the_ID(), '_project_url', true);
                    $client = get_post_meta(get_the_ID(), '_project_client', true);
                    $completion_date = get_post_meta(get_the_ID(), '_project_completion_date', true);
                    ?>

                    <div class="project-meta">
                        <?php if ($client) : ?>
                            <div class="meta-item">
                                <h5><?php _e('Client:', '_themename'); ?></h5>
                                <p><?php echo esc_html($client); ?></p>
                            </div>
                        <?php endif; ?>

                        <?php if ($completion_date) : ?>
                            <div class="meta-item">
                                <h5><?php _e('Completion Date:', '_themename'); ?></h5>
                                <p><?php echo esc_html($completion_date); ?></p>
                            </div>
                        <?php endif; ?>

                        <?php 
                        $terms = get_the_terms(get_the_ID(), 'project_type');
                        if ($terms && !is_wp_error($terms)) : ?>
                            <div class="meta-item">
                                <h5><?php _e('Project Type:', '_themename'); ?></h5>
                                <p><?php echo esc_html($terms[0]->name); ?></p>
                            </div>
                        <?php endif; ?>

                        <?php if ($project_url) : ?>
                            <div class="meta-item">
                                <a href="<?php echo esc_url($project_url); ?>" class="btn-primary" target="_blank">
                                    <?php _e('View Live Project', '_themename'); ?>
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-5">
            <div class="col-12">
                <div class="project-content">
                    <?php the_content(); ?>
                </div>
            </div>
        </div>
    </div>
</article>