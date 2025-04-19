<?php

/**
 * Automatically require all PHP files from /lib and /inc directories.
 */
function devabu_include_all_files() {
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
devabu_include_all_files();


?>