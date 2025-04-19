<?php
/**
 * Registers a custom post type for Portfolio items.
 * @package _themename
 */
function _themename_post_type_portfolio() {
    $labels = array(
        'name'               => __('Portfolio', '_themename'),
        'singular_name'      => __('Portfolio Item', '_themename'),
        'menu_name'          => __('Portfolio', '_themename'),
        'all_items'          => __('All Projects', '_themename'),
        'add_new'            => __('Add New Project', '_themename'),
        'add_new_item'       => __('Add New Portfolio Item', '_themename'),
        'edit_item'          => __('Edit Portfolio Item', '_themename'),
        'new_item'           => __('New Portfolio Item', '_themename'),
        'view_item'          => __('View Portfolio Item', '_themename'),
        'search_items'       => __('Search Portfolio', '_themename'),
        'not_found'          => __('No portfolio items found', '_themename'),
    );

    $args = array(
        'labels'              => $labels,
        'menu_icon'          => 'dashicons-portfolio',
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'show_in_rest'       => true,
        'query_var'          => true,
        'rewrite'            => array('slug' => 'portfolio'),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => 5,
        'supports'           => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'),
        'taxonomies'         => array('category', 'post_tag'),
    );

    register_post_type('portfolio', $args);

    // Register Project Type Taxonomy
    $taxonomy_labels = array(
        'name'              => __('Project Types', '_themename'),
        'singular_name'     => __('Project Type', '_themename'),
        'search_items'      => __('Search Project Types', '_themename'),
        'all_items'         => __('All Project Types', '_themename'),
        'parent_item'       => __('Parent Project Type', '_themename'),
        'parent_item_colon' => __('Parent Project Type:', '_themename'),
        'edit_item'         => __('Edit Project Type', '_themename'),
        'update_item'       => __('Update Project Type', '_themename'),
        'add_new_item'      => __('Add New Project Type', '_themename'),
        'new_item_name'     => __('New Project Type Name', '_themename'),
        'menu_name'         => __('Project Types', '_themename'),
    );

    $taxonomy_args = array(
        'hierarchical'      => true,
        'labels'            => $taxonomy_labels,
        'show_ui'          => true,
        'show_admin_column' => true,
        'query_var'        => true,
        'rewrite'          => array('slug' => 'project-type'),
        'show_in_rest'     => true,
    );

    register_taxonomy('project_type', array('portfolio'), $taxonomy_args);
}
add_action('init', '_themename_post_type_portfolio');