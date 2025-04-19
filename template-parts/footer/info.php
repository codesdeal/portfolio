<?php
    $footer_bg = devabu_sanitize_footer_bg( get_theme_mod( 'devabu_footer_bg', 'dark' ) );
    $site_info = get_theme_mod( 'devabu_site_info', '' );
?>
<?php if ( $site_info ) : ?>
    <div class="c-site-info c-site-info--<?php echo esc_attr( $footer_bg ); ?> footer-bottom-style">
        <div class="o-container">
            <div class="o-row">
                <div class="o-row__column o-row__column--span-12">
                    <?php echo devabu_sanitize_footer_info( $site_info ); ?>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>