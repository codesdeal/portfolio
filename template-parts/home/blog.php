<!-- blog post section-->
<section id="blog" class="testimonials-section section-gap">
    <div class="container">
        <div class="row g-4">
            <!-- Section Title -->
            <div class="title-sec">
                <h2 class="section-title"><?php esc_html_e("Blog", '_themename'); ?></h2>
            </div>
            <div class="top-title-sec">
                <?php echo get_theme_mod( '_themename_home_blog_overview', '' ); ?>
            </div>
                <?php
                    $blog_posts = new WP_Query( array(
                        'post_type'      => 'post',
                        'posts_per_page' => 5,
                        'orderby'        => 'date',
                        'order'          => 'ASC'
                    ) );

                    if ( $blog_posts->have_posts() ) :
                        while ( $blog_posts->have_posts() ) : $blog_posts->the_post(); ?>
                            <div class="col-md-4">
                                <div class="card img-card" data-aos="fade-up" data-aos-duration="1500" data-aos-delay="100"
                                    data-aos-anchor-placement="fade-up">
                                    
                                    <?php if ( has_post_thumbnail() ) : ?>
                                        <?php the_post_thumbnail( 'full', array( 'class' => 'card-img-top', 'alt' => get_the_title() ) ); ?>
                                    <?php endif; ?>

                                    <div class="card-body">
                                        <h3><?php the_title(); ?></h3>
                                        <h5><?php echo get_the_date(); ?></h5>
                                        <p><?php echo wp_trim_words( get_the_excerpt(), 25, '...' ); ?></p>
                                        <a href="<?php the_permalink(); ?>" class="link">Read More</a>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile;
                        wp_reset_postdata();
                    else :
                        echo '<p>No blog posts found.</p>';
                    endif;
                ?>

            <div class="button">
                <a href="#">READ MORE</a>
            </div>
        </div>
    </div>
</section>
<!-- ###blog post section-->