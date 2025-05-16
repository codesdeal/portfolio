<section id="hero" class="hero-section">
    <?php
    $desktop_image_id = get_theme_mod('_themename_home_banner_image', '');
    $mobile_image_id  = get_theme_mod('_themename_home_banner_image_mobile', $desktop_image_id); // fallback to desktop
    $desktop_image    = wp_get_attachment_image_url($desktop_image_id, 'full');
    $mobile_image     = wp_get_attachment_image_url($mobile_image_id, 'full');
    ?>

    <?php if ($desktop_image || $mobile_image): ?>
        <div class="image-background-wrapper">
            <?php if ($desktop_image): ?>
                <picture class="image-background desktop-bg">
                    <source srcset="<?php echo esc_url(wp_get_attachment_image_url($desktop_image_id, 'full', false)); ?>" type="image/webp">
                    <?php echo wp_get_attachment_image($desktop_image_id, 'full', false, ['class' => 'desktop-img', 'loading' => 'lazy']); ?>
                </picture>
            <?php endif; ?>
            <?php if ($mobile_image): ?>
                <picture class="image-background mobile-bg">
                    <source srcset="<?php echo esc_url(wp_get_attachment_image_url($mobile_image_id, 'full', false)); ?>" type="image/webp">
                    <?php echo wp_get_attachment_image($mobile_image_id, 'full', false, ['class' => 'mobile-img', 'loading' => 'lazy']); ?>
                </picture>
            <?php endif; ?>
        </div>
    <?php endif; ?>


    <div class="container-fluid px-0">
        <div class="row">
            <div class="col-12">
                <div class="container">
                    <?php $banner_text = get_theme_mod('_themename_home_banner_text', ''); ?>
                    <div class="hero-content" data-aos="fade-up">
                        <?php if ($banner_text): ?>
                            <p class="hero-txt">
                                <?php echo wp_kses_post($banner_text); ?>
                            </p>
                            <button onclick="window.open('https://www.upwork.com/freelancers/~01d1c4a90427d3dbdd', '_blank')" class="btn-view">
                                Hire on UpWork
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
