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

// add_action( 'init_language_setup', function() {
//     load_theme_textdomain( '_themename', get_template_directory() . '/languages' );
// } );

add_action('init', function(){
    $mo = '_themename-'. determine_locale() . '.mo';
    $path = get_template_directory(__FILE__) . 'languages/' .$mo ;
    load_textdomain( '_themename', $path  );
});


// add_action('init', function() {
// 	global $l10n, $wp_textdomain_registry;
// 	$domain = '_themename';
// 	$locale = get_locale();
// 	$wp_textdomain_registry->set($domain, $locale, get_template_directory() . '/languages');
// 	if ( isset( $l10n[ $domain ] )) {
// 	  unset( $l10n[ $domain ] );
// 	}
// 	load_theme_textdomain($domain, get_template_directory() . '/languages');
//   });




// Allow JSON uploads (for Lottie files)
function _themename_allow_json_uploads( $mimes ) {
    if ( current_user_can( 'manage_options' ) ) {
        $mimes['json'] = 'application/json';
    }
    return $mimes;
}
add_filter( 'upload_mimes', '_themename_allow_json_uploads' );


?>