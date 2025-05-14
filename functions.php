<?php

/**
 * Automatically require all PHP files from /lib and /inc directories.
 */
function _themename_include_all_files() {
	$directories = ['lib', 'inc', 'inc/post-types'];

	foreach ( $directories as $dir ) {
		$full_path = trailingslashit( get_template_directory() ) . $dir;

		if ( is_dir( $full_path ) ) {
			foreach ( glob( $full_path . '/*.php' ) as $file ) {
				require_once $file;
			}
		}
	}
}
_themename_include_all_files();

// Include the Mega Menu Walker
require_once get_template_directory() . '/inc/class-mega-menu-walker.php';

// add_image_size( 'portfolio-thumb', 600, 300, true ); // cropped image


?>