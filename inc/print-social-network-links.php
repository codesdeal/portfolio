<?php
/**
 * Display the social links saved in the customizer.
 *
 * @package devabu
 */


function print_social_network_links() {
    $social_networks = [ 'facebook', 'x-twitter', 'instagram', 'youtube', 'linkedin' ];

    echo '<div class="social-icons">';

    foreach ( $social_networks as $network ) {
        $url = get_theme_mod( "devabu_{$network}_link" );
        if ( ! empty( $url ) ) {
            echo '<a href="' . esc_url( $url ) . '" target="_blank" rel="noopener noreferrer">';
            echo '<i class="fa-brands fa-' . esc_attr( $network ) . '"></i>';
            echo '</a>';
        }
    }

    echo '</div>';
}
