<!-- hero section -->
<section id="hero" class="hero-section">
    <div class="container-fluid px-0">
        <div class="row">
            <div class="col-md-12">
                <?php 
                $banner_image_id = get_theme_mod('_themename_home_banner_image', '');

                if ($banner_image_id) :
                    $image = wp_get_attachment_image_src($banner_image_id, 'full');
                    if ($image) : ?>
                        <div class="image-background" style="background-image: url('<?php echo esc_url($image[0]); ?>');"></div>
                    <?php endif;
                endif;
                ?>

                <div class="container">
                    <?php $banner_text = get_theme_mod('_themename_home_banner_text', ''); ?>
                    <div class="hero-content col-md-7" data-aos="fade-up">
                        <?php if ($banner_text) : ?>
                            <p class="hero-txt">
                                <?php echo wp_kses_post($banner_text); ?>
                                <button href="https://www.upwork.com/freelancers/~01d1c4a90427d3dbdd" target="_blank" class="btn-view">Hire on UpWork</button>
                            </p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- ###hero section -->