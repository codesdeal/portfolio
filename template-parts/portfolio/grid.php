<?php
/**
 * Template part for displaying portfolio grid
 * @package _themename
 */
?>

<section id="portfolio" class="portfolio-grid section-gap">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="title-sec">
                    <h2 class="section-title"><?php esc_html_e("Project Showcase", "_themename"); ?></h2>                        
                </div>
            </div>
        </div>
        <div class="portfolio-filters mb-5">
            <?php
            $terms = get_terms('project_type');
            if (!empty($terms) && !is_wp_error($terms)) : ?>
                <ul class="filter-buttons">
                    <li><button class="active" data-filter="*"><?php _e('All', '_themename'); ?></button></li>
                    <?php foreach ($terms as $term) : ?>
                        <li><button data-filter=".<?php echo esc_attr($term->slug); ?>">
                            <?php echo esc_html($term->name); ?>
                        </button></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>

        <div class="row g-4 portfolio-items">
            <?php
            $portfolio_query = new WP_Query([
                'post_type' => 'portfolio',
                'posts_per_page' => -1,
                'orderby' => 'date',
                'order' => 'ASC',
            ]);

            if ($portfolio_query->have_posts()) :
                while ($portfolio_query->have_posts()) : $portfolio_query->the_post();
                    $terms = get_the_terms(get_the_ID(), 'project_type');
                    $term_classes = '';
                    if ($terms && !is_wp_error($terms)) {
                        $term_slugs = array_map(function($term) {
                            return $term->slug;
                        }, $terms);
                        $term_classes = implode(' ', $term_slugs);
                    }
                    ?>
                    <div class="col-md-4 portfolio-item <?php echo esc_attr($term_classes); ?>">
                        <div class="card" data-aos="fade-up">
                            <?php if (has_post_thumbnail()) : ?>
                                <div class="card-img">
                                    <?php the_post_thumbnail('large', ['class' => 'img-fluid']); ?>
                                    <div class="card-img-overlay">
                                        <div class="overlay-content">
                                            <h3><?php the_title(); ?></h3>
                                            <?php if ($terms) : ?>
                                                <p class="project-type">
                                                    <?php echo esc_html($terms[0]->name); ?>
                                                </p>
                                            <?php endif; ?>
                                            <a href="<?php the_permalink(); ?>" class="btn-view">
                                                <?php _e('View Project', '_themename'); ?>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endwhile;
                wp_reset_postdata();
            endif;
            ?>
        </div>
    </div>
</section>