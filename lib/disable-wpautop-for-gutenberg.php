<?php
/**
 * Disable wpautop for Gutenberg blocks.
 *
 * @package _themename
 */
/**
 * Disables wpautop to remove empty p tags in rendered Gutenberg blocks.
 *
 * @author Corey Collins
 */
function disable_wpautop_for_gutenberg() {
	// If we have blocks in place, don't add wpautop.
	if ( has_filter( 'the_content', 'wpautop' ) && has_blocks() ) {
		remove_filter( 'the_content', 'wpautop' );
	}
}

add_filter( 'init', 'disable_wpautop_for_gutenberg', 9 );


// function _themename_remove_wpautop_on_front_page() {
// 	if ( is_front_page() ) {
// 		remove_filter( 'the_content', 'wpautop' );
// 		remove_filter( 'the_excerpt', 'wpautop' );
// 	}
// }
// add_action( 'template_redirect', '_themename_remove_wpautop_on_front_page' );
