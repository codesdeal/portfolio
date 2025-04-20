<?php
/**
 * SEO helper functions for meta tags and Open Graph
 */

if (!function_exists('_themename_get_meta_description')) {
    function _themename_get_meta_description() {
        global $post;
        $description = '';

        if (is_singular()) {
            // Use excerpt if available, otherwise get first 160 chars of content
            $description = !empty($post->post_excerpt) ? $post->post_excerpt : wp_trim_words(strip_tags($post->post_content), 20);
        } elseif (is_category() || is_tag() || is_tax()) {
            $description = strip_tags(term_description());
        } else {
            $description = get_bloginfo('description');
        }

        return wp_strip_all_tags(substr($description, 0, 160));
    }
}

if (!function_exists('_themename_output_og_tags')) {
    function _themename_output_og_tags() {
        global $post;

        // Default values
        $og_type = 'website';
        $og_title = get_bloginfo('name');
        $og_url = home_url('/');
        $og_description = _themename_get_meta_description();
        $og_image = get_site_icon_url();

        // Specific page values
        if (is_singular()) {
            $og_type = 'article';
            $og_title = get_the_title();
            $og_url = get_permalink();
            
            if (has_post_thumbnail()) {
                $og_image = get_the_post_thumbnail_url(null, 'large');
            }
        }

        // Output tags
        echo '<meta property="og:type" content="' . esc_attr($og_type) . '" />' . "\n";
        echo '<meta property="og:title" content="' . esc_attr($og_title) . '" />' . "\n";
        echo '<meta property="og:description" content="' . esc_attr($og_description) . '" />' . "\n";
        echo '<meta property="og:url" content="' . esc_url($og_url) . '" />' . "\n";
        echo '<meta property="og:image" content="' . esc_url($og_image) . '" />' . "\n";
        
        // Twitter Cards
        echo '<meta name="twitter:card" content="summary_large_image" />' . "\n";
        echo '<meta name="twitter:title" content="' . esc_attr($og_title) . '" />' . "\n";
        echo '<meta name="twitter:description" content="' . esc_attr($og_description) . '" />' . "\n";
        echo '<meta name="twitter:image" content="' . esc_url($og_image) . '" />' . "\n";
    }
}

// Add WebP support check
if (!function_exists('_themename_browser_supports_webp')) {
    function _themename_browser_supports_webp() {
        $accept = isset($_SERVER['HTTP_ACCEPT']) ? $_SERVER['HTTP_ACCEPT'] : '';
        return strpos($accept, 'image/webp') !== false;
    }
}