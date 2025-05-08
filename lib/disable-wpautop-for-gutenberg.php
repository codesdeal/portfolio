<?php
/**
 * Disables wpautop to remove empty p tags in rendered Gutenberg blocks.
 *
 * @package _themename
 */
function _themename_disable_wpautop_for_gutenberg() {
	// If we have blocks in place, don't add wpautop.
	if ( has_filter( 'the_content', 'wpautop' ) && has_blocks() ) {
		remove_filter( 'the_content', 'wpautop' );
	}
}

add_filter( 'init', '_themename_disable_wpautop_for_gutenberg', 9 );
