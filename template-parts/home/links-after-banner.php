<!-- cp block -->
<section id="cp" class="section-gap">
    <div class="container">
        <?php $link_after_banner = get_theme_mod( '_themename_home_banner_links_html', '' ); ?>
            <div class="row">
                <?php if ( $link_after_banner ) : ?>            
                    <div class="col-12">
                        <?php echo _themename_sanitize_footer_info( $link_after_banner ); ?>
                    </div>
                <?php endif; ?>
            </div>
    </div>
</section>
<!-- ###cp block -->
