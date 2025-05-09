<?php
function _themename_assets() {
    wp_enqueue_style('_themename-style', get_stylesheet_uri(), array(), '1.0.0', 'all');
    wp_enqueue_style('_themename-stylesheet', get_template_directory_uri() . '/dist/assets/css/bundle.css', array(), '1.0.0', 'all');
    
    wp_enqueue_script('jquery');   
    wp_enqueue_script('_themename-scripts', get_template_directory_uri() . '/dist/assets/js/bundle.js', array('jquery'), '1.0.0', true);
    wp_enqueue_script('_themename-lottie', get_template_directory_uri() . '/dist/assets/js/lottie.min.js', array(), '1.0.0', true);

    wp_localize_script('_themename-scripts', '_themename', array(
        'nonce' => wp_create_nonce('theme_error_logging'),
        'ajaxurl' => admin_url('admin-ajax.php')
    ));
}
add_action('wp_enqueue_scripts', '_themename_assets');

function _themename_admin_assets() {
    wp_enqueue_style('_themename-admin-stylesheet', get_template_directory_uri() . '/dist/assets/css/admin.css', array(), '1.0.0', 'all');

    wp_enqueue_script('_themename-admin-scripts', get_template_directory_uri() . '/dist/assets/js/admin.js', array('jquery'), '1.0.0', true);
}
add_action('admin_enqueue_scripts', '_themename_admin_assets');


function _themename_customize_preview_js() {
    wp_enqueue_script( '_themename-customize-preview', get_template_directory_uri() . '/dist/assets/js/customize-preview.js', array( 'customize-preview', 'jquery' ), '1.0.0', true );
}
add_action( 'customize_preview_init', '_themename_customize_preview_js' );