<?php
/**
 * Performance optimizations for the theme
 */

// Register and load service worker
function _themename_register_service_worker() {
    if (!is_admin()) {
        // Register the service worker
        wp_register_script(
            'service-worker',
            get_template_directory_uri() . '/dist/assets/js/service-worker.js',
            array(),
            '1.0.0',
            true
        );

        // Add service worker configuration
        $sw_config = array(
            'cacheName' => '_themename_cache_v1',
            'debugMode' => WP_DEBUG,
            'offlinePage' => home_url('/offline/'),
            'cacheFirst' => array(
                'fonts',
                'images',
                'static'
            )
        );

        wp_localize_script('service-worker', 'swConfig', $sw_config);
        wp_enqueue_script('service-worker');
    }
}
add_action('wp_enqueue_scripts', '_themename_register_service_worker');

// Add browser caching headers with improved configuration
function _themename_add_browser_caching() {
    if (!is_admin()) {
        $cache_time = array(
            'default' => 2592000,    // 30 days
            'css' => 31536000,       // 1 year
            'js' => 31536000,        // 1 year
            'images' => 15552000,    // 180 days
            'fonts' => 31536000,     // 1 year
        );

        $file_type = strtolower(pathinfo($_SERVER['REQUEST_URI'], PATHINFO_EXTENSION));
        
        // Set appropriate cache times based on file type
        $cache_duration = isset($cache_time[$file_type]) ? $cache_time[$file_type] : $cache_time['default'];
        
        // Set cache control headers
        header('Cache-Control: public, max-age=' . $cache_duration . ', s-maxage=' . $cache_duration);
        header('Expires: ' . gmdate('D, d M Y H:i:s', time() + $cache_duration) . ' GMT');
        
        // Add Vary header for better caching
        header('Vary: Accept-Encoding');
        
        // Enable gzip compression
        if (extension_loaded('zlib') && !ini_get('zlib.output_compression')) {
            ini_set('zlib.output_compression', 'On');
        }
    }
}
add_action('template_redirect', '_themename_add_browser_caching');

// Resource hints for external domains
function _themename_add_resource_hints($urls, $relation_type) {
    if ('preconnect' === $relation_type) {
        // Add preconnect for Google Fonts
        $urls[] = array(
            'href' => 'https://fonts.googleapis.com',
            'crossorigin' => true,
        );
        $urls[] = array(
            'href' => 'https://fonts.gstatic.com',
            'crossorigin' => true,
        );
        
        // Add preconnect for common CDNs if used
        if (wp_script_is('jquery', 'enqueued')) {
            $urls[] = array(
                'href' => 'https://code.jquery.com',
                'crossorigin' => true,
            );
        }
    }
    
    // Add prefetch for important pages
    if ('prefetch' === $relation_type) {
        // Prefetch next/previous posts for better navigation
        if (is_single()) {
            $next_post = get_next_post();
            if ($next_post) {
                $urls[] = array(
                    'href' => get_permalink($next_post),
                );
            }
        }
    }
    
    return $urls;
}
add_filter('wp_resource_hints', '_themename_add_resource_hints', 10, 2);

// Optimize image loading
function _themename_optimize_image_loading($attr, $attachment, $size) {
    // Add loading attribute for images
    $attr['loading'] = 'lazy';
    $attr['decoding'] = 'async';
    
    // Add srcset for responsive images
    if (!isset($attr['srcset']) && !is_admin()) {
        $srcset = wp_get_attachment_image_srcset($attachment->ID, $size);
        if ($srcset) {
            $attr['srcset'] = $srcset;
            $attr['sizes'] = wp_get_attachment_image_sizes($attachment->ID, $size);
        }
    }
    
    return $attr;
}
add_filter('wp_get_attachment_image_attributes', '_themename_optimize_image_loading', 10, 3);

// Add critical CSS inline
// function _themename_add_critical_css() {
//     $critical_css_path = get_template_directory() . '/dist/assets/css/critical.css';
    
//     if (file_exists($critical_css_path)) {
//         $critical_css = file_get_contents($critical_css_path);
//         if ($critical_css) {
//             printf(
//                 '<style id="critical-css">%s</style>',
//                 wp_strip_all_tags($critical_css)
//             );
//         }
//     }
// }
// add_action('wp_head', '_themename_add_critical_css', 1);

// Preload critical assets
function _themename_preload_assets() {
    $preload_files = array(
        // array(
        //     'type' => 'style',
        //     'path' => '/dist/assets/css/critical.css',
        //     'as' => 'style'
        // ),
        array(
            'type' => 'script',
            'path' => '/dist/assets/js/bundle.js',
            'as' => 'script'
        ) //,
        // array(
        //     'type' => 'font',
        //     'path' => '/dist/assets/webfonts/urbanist-v10-latin-regular.woff2',
        //     'as' => 'font',
        //     'crossorigin' => true
        // )
    );
    
    foreach ($preload_files as $file) {
        $url = get_template_directory_uri() . $file['path'];
        printf(
            '<link rel="preload" href="%s" as="%s"%s />',
            esc_url($url),
            esc_attr($file['as']),
            isset($file['crossorigin']) ? ' crossorigin' : ''
        );
    }
}
add_action('wp_head', '_themename_preload_assets', 1);