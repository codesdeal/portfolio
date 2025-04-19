<?php
/**
 * Registers a custom post type for FAQs.
 * @package devabu
 */
function devabu_post_type_faq() {
    $labels = array(
        'name'               => __('FAQs', 'devabu'),
        'singular_name'      => __('FAQ', 'devabu'),
        'menu_name'          => __('FAQs', 'devabu'),
        'all_items'          => __('All FAQs', 'devabu'),
        'add_new'            => __('Add New FAQ', 'devabu'),
        'add_new_item'       => __('Add FAQ', 'devabu'),
        'edit_item'          => __('Edit FAQ', 'devabu'),
        'new_item'           => __('New FAQ', 'devabu'),
        'view_item'          => __('View FAQ', 'devabu'),
        'not_found'          => __('No FAQs Found', 'devabu'),
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
add_action('init', 'devabu_post_type_faq');
