<?php
/**
 * Template part for displaying testimonials
 * 
 * @package _themename
 */
?>

<section id="testimonials" class="testimonials-section section-gap">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <?php
                    $testimonials = new WP_Query([
                        'post_type'      => 'testimonial',
                        'posts_per_page' => -1,
                        'orderby'        => 'date',
                        'order'          => 'DESC',
                    ]);
                ?>

                <div class="testimonials-slider swiper" data-aos="fade-up">
                    <?php if ( $testimonials->have_posts() ) : ?>
                        <!-- Section Title -->
                        <div class="title-sec">
                            <h2 class="section-title"><?php esc_html_e("Client's Remarks", "_themename"); ?></h2>
                        </div>
                        <?php echo get_theme_mod('_themename_testimonials_overview', ''); ?>
                        <div class="swiper-wrapper">
                            <?php while ( $testimonials->have_posts() ) : $testimonials->the_post(); ?>
                                <div class="swiper-slide">
                                    <div class="testimonial-card">
                                        
                                        <div class="testimonial-content">
                                            <?php the_content(); ?>
                                        </div>

                                        <?php if ( has_post_thumbnail() ) : ?>
                                            <div class="testimonial-author-img">
                                                <?php the_post_thumbnail('thumbnail', ['class' => 'rounded-circle']); ?>
                                            </div>
                                        <?php endif; ?>

                                        <div class="testimonial-author">
                                            <h4><?php the_title(); ?></h4>
                                            <?php
                                            $position = get_post_meta( get_the_ID(), '_testimonial_position', true );
                                            if ( $position ) :
                                            ?>
                                                <p><?php echo esc_html( $position ); ?></p>
                                            <?php endif; ?>
                                        </div>

                                    </div>
                                </div>
                            <?php endwhile; ?>
                        </div>

                        <!-- Swiper Navigation -->
                        <div class="swiper-button-prev"></div>
                        <div class="swiper-button-next"></div>
                        <div class="swiper-pagination"></div>

                        <?php wp_reset_postdata(); ?>

                    <?php else : ?>
                        <p><?php esc_html_e('No testimonials found.', '_themename'); ?></p>
                    <?php endif; ?>
                </div>

            </div>
        </div>
    </div>
</section>
