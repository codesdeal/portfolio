<?php
/**
 * The function registers a custom post type for slider images in WordPress with specific labels,
 * settings, and capabilities.
 *  @package devabu
 */
function devabu_post_type_company_logo() {
    $labels = array(
        'name'               => __('Company Logo', 'devabu'),
        'singular_name'      => __('Company Logo', 'devabu'),
        'menu_name'          => __('Company Logo', 'devabu'),
        'all_items'          => __('All Company Logos', 'devabu'),
        'add_new'            => __('Add New Logo', 'devabu'),
        'add_new_item'       => __('Add Company Logo', 'devabu'),
        'edit_item'          => __('Edit Company Logo', 'devabu'),
        'new_item'           => __('New Company Logo', 'devabu'),
        'view_item'          => __('View Company Logo', 'devabu'),
        'not_found'          => __('No Company Logo Found', 'devabu'),
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
add_action('init', 'devabu_post_type_company_logo');