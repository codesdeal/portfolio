<?php
/**
 * Debug and error logging helpers
 */

if (!function_exists('_themename_log_error')) {
    function _themename_log_error($message, $data = null) {
        if (WP_DEBUG === true) {
            $log_entry = date('[Y-m-d H:i:s] ') . $message;
            if ($data) {
                $log_entry .= ': ' . print_r($data, true);
            }
            error_log($log_entry . PHP_EOL, 3, get_template_directory() . '/error.log');
        }
    }
}

if (!function_exists('_themename_debug_to_console')) {
    function _themename_debug_to_console($data, $context = 'Debug Info') {
        if (WP_DEBUG === true) {
            // Buffering to solve problems frameworks, like header() in this case.
            ob_start();
            $output = 'console.info(' . json_encode($context) . ');';
            $output .= 'console.log(' . json_encode($data) . ');';
            $output = sprintf('<script>%s</script>', $output);
            echo $output;
            ob_end_flush();
        }
    }
}

if (!function_exists('_themename_get_performance_metrics')) {
    function _themename_get_performance_metrics() {
        if (WP_DEBUG === true) {
            global $wpdb;
            $metrics = array(
                'queries' => $wpdb->num_queries,
                'memory_usage' => memory_get_peak_usage(true),
                'load_time' => timer_stop(0, 3)
            );
            _themename_debug_to_console($metrics, 'Performance Metrics');
        }
    }
}
add_action('wp_footer', '_themename_get_performance_metrics', 999);