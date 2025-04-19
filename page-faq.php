<?php

/**
 * Template Name: FAQ Page
 *
 * The template for displaying the FAQ.
 *
 * @package _themename
 */

get_header();

?>

<section id="dine-with-us" class="top-section-gap">
    <div class="container">
        <div>
            <h1 data-aos="fade-up" data-aos-delay="200" class="mb-5 text-center">Frequently Asked Question</h1>
            <div class="accordion-custom">                
                <div class="accordion" id="accordionExample">
                    <div class="row">
                        <?php
                        $faq_query = new WP_Query([
                            'post_type'      => 'faq',
                            'posts_per_page' => -1,
                        ]);

                        if ( $faq_query->have_posts() ) :
                            $i = 0;

                            while ( $faq_query->have_posts() ) :
                                $faq_query->the_post();
                                $i++;

                                $faq_id     = 'collapse' . $i;
                                $heading_id = 'heading' . $i;
                                $is_first   = ( $i === 1 );
                                $expanded   = $is_first ? 'true' : 'false';
                                $collapsed  = $is_first ? '' : 'collapsed';
                                $show_class = $is_first ? 'show' : '';
                                ?>
                                <div class="col-md-6">
                                    <div class="accordion-item mb-4">
                                        <h2 class="accordion-header" id="<?php echo esc_attr( $heading_id ); ?>">
                                            <button
                                                class="accordion-button <?php echo esc_attr( $collapsed ); ?>"
                                                type="button"
                                                data-bs-toggle="collapse"
                                                data-bs-target="#<?php echo esc_attr( $faq_id ); ?>"
                                                aria-expanded="<?php echo esc_attr( $expanded ); ?>"
                                                aria-controls="<?php echo esc_attr( $faq_id ); ?>"
                                            >
                                                <?php the_title(); ?>
                                            </button>
                                        </h2>
                                        <div
                                            id="<?php echo esc_attr( $faq_id ); ?>"
                                            class="accordion-collapse collapse <?php echo esc_attr( $show_class ); ?>"
                                            aria-labelledby="<?php echo esc_attr( $heading_id ); ?>"
                                            data-bs-parent="#accordionExample"
                                        >
                                            <div class="accordion-body">
                                                <?php the_content(); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php
                            endwhile;

                            wp_reset_postdata();
                        endif;
                        ?>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>

<?php get_footer(); ?>