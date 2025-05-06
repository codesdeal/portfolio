    <div class="o-container">
        <div class="o-row">        
            <!-- Brand Logo ans Social Buttons -->
            <div class="o-row__column o-row__column--span-12 o-row__column--span-3@medium mobile-spacing">
                <div class="link-wrapper">
                    <?php
                        $footer_logo = get_theme_mod('_themename_footer_logo');
                        if ( $footer_logo ) {
                            echo '<a class="c-footer__logo" href="' . esc_url( home_url( '/' ) ) . '">';
                            echo '<img src="' . esc_url( $footer_logo ) . '" alt="' . esc_attr( get_bloginfo('name') ) . '">';
                            echo '</a>';
                        } else {
                            ?>
                            <a class="c-footer__blogname" href="<?php echo esc_url( home_url( '/' ) ); ?>">
                                <?php bloginfo( 'name' ); ?>
                            </a>
                            <?php
                        }
                    ?>
                    <div class="social-buttons">
                        <?php print_social_network_links(); ?>
                    </div>
                </div>
            </div>

            <!-- Office Address and Contact -->
            <?php for ( $i = 2; $i <= 4; $i++ ) : ?>
                <div class="o-row__column o-row__column--span-12 o-row__column--span-3@medium mobile-spacing">
                    <div class="off-address">
                        <?php echo wp_kses_post( get_theme_mod( "_themename_footer_info_col_$i", '' ) ); ?>
                    </div>
                </div>
            <?php endfor; ?>
        </div>
    </div>