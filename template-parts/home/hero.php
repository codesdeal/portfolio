<!-- hero section -->
<section id="hero" class="hero-section">
    <div class="container-fluid px-0">
        <div class="row">
            <?php 
            $banner_media_id = get_theme_mod('_themename_home_banner_media', '');
            $banner_image_id = get_theme_mod('_themename_home_banner_image', '');
            
            // Debug output
            if (WP_DEBUG) {
                error_log('Banner Media ID: ' . print_r($banner_media_id, true));
                error_log('Banner Image ID: ' . print_r($banner_image_id, true));
            }
            ?>
            <div class="col-12">
                <?php if ($banner_media_id) : 
                    $video_url = wp_get_attachment_url($banner_media_id);
                    if ($video_url) : ?>
                        <video class="video-background" autoplay muted loop playsinline>
                            <source src="<?php echo esc_url($video_url); ?>" type="video/mp4">
                        </video>
                    <?php endif;
                elseif ($banner_image_id) :
                    $image = wp_get_attachment_image_src($banner_image_id, 'full');
                    if ($image) :
                        // Debug output
                        if (WP_DEBUG) {
                            error_log('Banner Image Data: ' . print_r($image, true));
                        }
                    ?>
                        <div class="image-background" style="background-image: url('<?php echo esc_url($image[0]); ?>');"></div>
                    <?php endif;
                endif; ?>

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