<?php

function _themename_theme_support() {
    // Core theme features
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', array(
        'search-form',
        'comment-list',
        'comment-form',
        'gallery',
        'caption',
        'script',
        'style',
        'navigation-widgets'
    ));
    
    // Modern image formats
    add_theme_support('responsive-embeds');
    add_theme_support('custom-line-height');
    add_theme_support('experimental-link-color');
    add_theme_support('custom-units');
    add_theme_support('custom-spacing');
    
    // Custom logo with better defaults
    add_theme_support('custom-logo', array(
        'height' => 100,
        'width' => 300,
        'flex-height' => true,
        'flex-width' => true,
        'unlink-homepage-logo' => true,
    ));

    // Block editor features
    add_theme_support('align-wide');
    add_theme_support('editor-styles');
    add_theme_support('wp-block-styles');
    add_theme_support('responsive-embeds');
    add_editor_style('dist/assets/css/editor.css');

    // Performance optimizations
    add_theme_support('automatic-feed-links');
    add_theme_support('core-block-patterns');
    add_theme_support('custom-units');
    
    // Post formats with modern defaults
    add_theme_support('post-formats', array(
        'aside',
        'image',
        'video',
        'quote',
        'link',
        'gallery',
        'audio',
    ));

    // Register nav menus
    register_nav_menus(array(
        'main-menu' => esc_html__('Main Menu', '_themename'),
        'footer-menu' => esc_html__('Footer Menu', '_themename')
    ));
}
add_action('after_setup_theme', '_themename_theme_support');