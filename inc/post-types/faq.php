<?php
/**
 * Registers a custom post type for FAQs.
 * @package devabu
 */
function _themename_post_type_faq() {
    $labels = array(
        'name'               => __('FAQs', '_themename'),
        'singular_name'      => __('FAQ', '_themename'),
        'menu_name'          => __('FAQs', '_themename'),
        'all_items'          => __('All FAQs', '_themename'),
        'add_new'            => __('Add New FAQ', '_themename'),
        'add_new_item'       => __('Add FAQ', '_themename'),
        'edit_item'          => __('Edit FAQ', '_themename'),
        'new_item'           => __('New FAQ', '_themename'),
        'view_item'          => __('View FAQ', '_themename'),
        'not_found'          => __('No FAQs Found', '_themename'),
    );

    $args = array(
        'labels'             => $labels,
        'menu_icon'          => 'dashicons-editor-help',
        'public'             => true,
        'publicly_queryable' => false,
        'menu_position'      => 9,
        'has_archive'        => false,
        'hierarchical'       => false,
        'show_ui'            => true,
        'show_in_rest'       => true,
        'capability_type'    => 'post',
        'rewrite'            => false,
        'supports'           => array('title', 'editor'),
        'show_in_nav_menus'  => false,
    );
    register_post_type('faq', $args);
}
add_action('init', '_themename_post_type_faq');
