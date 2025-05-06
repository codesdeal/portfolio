<!-- hero section -->
<section id="hero" class="hero-section">
    <div class="container-fluid px-0">
        <div class="row">
            <div class="col-12">
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
                    <div class="hero-content col-lg-6">
                        <?php if ($banner_text) : ?>
                            <h1 class="hero-txt">
                                <?php echo wp_kses_post($banner_text); ?>
                            </h1>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- ###hero section -->
