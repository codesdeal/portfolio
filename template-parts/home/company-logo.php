<!-- Home Features section -->
<section id="our_clients" class="section-gap">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="title-sec" data-aos="fade-up">
                    <h2 class="section-title"><?php esc_html_e("Partners in Excellence", '_themename'); ?></h2>                          
                </div>
                <div class="client-logo-sec" data-aos="fade-left">
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
    </div>
</section>
<!-- ###Home Features section -->
