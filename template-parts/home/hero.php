<!-- hero section -->
<section id="hero" class="hero-section">
    <div class="container-fluid px-0">
        <div class="row">
            <?php $banner_media_url = get_theme_mod( '_themename_home_banner_media', '' ); ?>
            <div class="col-12">
                <?php if ( $banner_media_url ) : ?>
                    <video class="video-background" autoplay muted loop>
                        <source src="<?php echo esc_url( $banner_media_url ); ?>" type="video/mp4">
                    </video>
                <?php endif; ?>

                <div class="container">
                    <?php $banner_text= get_theme_mod( '_themename_home_banner_text', '' ); ?>
                    <div class="hero-content col-lg-6">
                        <?php if ( $banner_text ) : ?>
                            <h1 class="hero-txt">
                                <?php echo esc_html__( $banner_text ) ?>
                            </h1>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- ###hero section -->