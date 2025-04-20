<?php
/**
 * Schema markup helper functions
 */

if (!function_exists('_themename_get_schema_markup')) {
    function _themename_get_schema_markup($type) {
        switch ($type) {
            case 'article':
                return [
                    '@context' => 'https://schema.org',
                    '@type' => 'Article',
                    'headline' => get_the_title(),
                    'author' => [
                        '@type' => 'Person',
                        'name' => get_the_author()
                    ],
                    'datePublished' => get_the_date('c'),
                    'dateModified' => get_the_modified_date('c'),
                    'image' => get_the_post_thumbnail_url(null, 'large'),
                    'publisher' => [
                        '@type' => 'Organization',
                        'name' => get_bloginfo('name'),
                        'logo' => [
                            '@type' => 'ImageObject',
                            'url' => get_site_icon_url()
                        ]
                    ]
                ];
            case 'website':
                return [
                    '@context' => 'https://schema.org',
                    '@type' => 'WebSite',
                    'name' => get_bloginfo('name'),
                    'description' => get_bloginfo('description'),
                    'url' => home_url()
                ];
            default:
                return [];
        }
    }
}

if (!function_exists('_themename_output_schema_markup')) {
    function _themename_output_schema_markup($type) {
        $schema = _themename_get_schema_markup($type);
        if (!empty($schema)) {
            echo '<script type="application/ld+json">' . wp_json_encode($schema) . '</script>';
        }
    }
}