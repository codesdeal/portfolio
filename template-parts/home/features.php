<!-- Home Features section -->
<section id="dine-with-us" class="section-gap">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="top-title-sec">
                    <?php echo get_theme_mod( 'devabu_home_company_logo_overview', '' ); ?>
                </div>

                <div class="img-sec">
                    <?php
                        $company_logos = new WP_Query( array(
                            'post_type'      => 'company_logo',
                            'posts_per_page' => -1,
                            'orderby'        => 'date',
                            'order'          => 'ASC',
                        ) );

                        if ( $company_logos->have_posts() ) :
                            while ( $company_logos->have_posts() ) : $company_logos->the_post();
                                if ( has_post_thumbnail() ) {
                                    the_post_thumbnail( 'full', array( 'class' => 'img-fluid' ) );
                                }
                            endwhile;
                            wp_reset_postdata();
                        else :
                            echo '<p>No company logos found.</p>';
                        endif;
                    ?>
                </div>

            </div>
        </div>

        <?php the_content(); ?>              
    </div>
</section>
<!-- ###Home Features section -->