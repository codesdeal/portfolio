<?php
/**
 * The function registers a custom post type for slider images in WordPress with specific labels,
 * settings, and capabilities.
 *  @package devabu
 */
function _themename_post_type_company_logo() {
    $labels = array(
        'name'               => __('Company Logo', '_themename'),
        'singular_name'      => __('Company Logo', '_themename'),
        'menu_name'          => __('Company Logo', '_themename'),
        'all_items'          => __('All Company Logos', '_themename'),
        'add_new'            => __('Add New Logo', '_themename'),
        'add_new_item'       => __('Add Company Logo', '_themename'),
        'edit_item'          => __('Edit Company Logo', '_themename'),
        'new_item'           => __('New Company Logo', '_themename'),
        'view_item'          => __('View Company Logo', '_themename'),
        'not_found'          => __('No Company Logo Found', '_themename'),
    );

    $args = array(
        'labels'             => $labels,
        'menu_icon'          => 'dashicons-format-image',
        'public'             => true,
        'publicly_queryable' => false,
        'menu_position'      => 8,
        'has_archive'        => false,
        'hierarchical'       => false,
        'show_ui'            => true,
        'show_in_rest'       => true,
        'capability_type'    => 'post',
        'rewrite'            => false,
        'supports'           => array('title', 'thumbnail', 'editor'), 
        'show_in_nav_menus'  => false,
    );
    register_post_type('company_logo', $args);
}
add_action('init', '_themename_post_type_company_logo');