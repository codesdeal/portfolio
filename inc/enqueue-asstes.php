<?php
function _themename_assets() {
    wp_enqueue_style('devabu-style', get_stylesheet_uri(), array(), '1.0.0', 'all');

    wp_enqueue_style('devabu-stylesheet', get_template_directory_uri() . '/dist/assets/css/bundle.css', array(), '1.0.0', 'all');

    wp_enqueue_script('jquery');
    wp_enqueue_script('devabu-scripts', get_template_directory_uri() . '/dist/assets/js/bundle.js', array('jquery'), '1.0.0', true);
}
add_action('wp_enqueue_scripts', '_themename_assets');

function _themename_admin_assets() {
    wp_enqueue_style('devabu-admin-stylesheet', get_template_directory_uri() . '/dist/assets/css/admin.css', array(), '1.0.0', 'all');

    wp_enqueue_script('devabu-admin-scripts', get_template_directory_uri() . '/dist/assets/js/admin.js', array('jquery'), '1.0.0', true);
}
add_action('admin_enqueue_scripts', '_themename_admin_assets');


function _themename_customize_preview_js() {
    wp_enqueue_script( 'devabu-customize-preview', get_template_directory_uri() . '/dist/assets/js/customize-preview.js', array( 'customize-preview', 'jquery' ), '1.0.0', true );
}
add_action( 'customize_preview_init', '_themename_customize_preview_js' );

// Add Google Font
function _themename_add_google_font_fontawesome() {
    // wp_enqueue_style('_themename_google_font', 'https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap', false);
    // wp_enqueue_style('_themename_fontawesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css', false);
}
add_action('wp_enqueue_scripts', '_themename_add_google_font_fontawesome');