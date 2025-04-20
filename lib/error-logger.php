<?php
/**
 * Error logging functionality for the theme
 */

if (!defined('ABSPATH')) {
    exit;
}

class ThemeErrorLogger {
    private static $instance = null;
    private $log_directory;
    private $max_log_size = 10485760; // 10MB

    private function __construct() {
        $upload_dir = wp_upload_dir();
        $this->log_directory = $upload_dir['basedir'] . '/theme-logs';
        
        if (!file_exists($this->log_directory)) {
            wp_mkdir_p($this->log_directory);
            
            // Create .htaccess to prevent direct access
            $htaccess_content = "Order deny,allow\nDeny from all";
            file_put_contents($this->log_directory . '/.htaccess', $htaccess_content);
        }
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function init() {
        add_action('wp_ajax_log_client_error', array($this, 'handle_client_error'));
        add_action('wp_ajax_nopriv_log_client_error', array($this, 'handle_client_error'));
        add_action('admin_menu', array($this, 'add_error_logs_page'));
    }

    public function handle_client_error() {
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'theme_error_logging')) {
            wp_send_json_error('Invalid nonce');
        }

        $raw_data = file_get_contents('php://input');
        $data = json_decode($raw_data, true);

        if (!$data || !isset($data['errors'])) {
            wp_send_json_error('Invalid data format');
        }

        foreach ($data['errors'] as $error) {
            $this->log_error($error);
        }

        wp_send_json_success();
    }

    private function log_error($error) {
        $log_file = $this->log_directory . '/errors.log';
        
        // Rotate log if too large
        if (file_exists($log_file) && filesize($log_file) > $this->max_log_size) {
            $this->rotate_logs();
        }

        $log_entry = array_merge($error, array(
            'timestamp' => current_time('mysql'),
            'user_id' => get_current_user_id(),
        ));

        error_log(
            wp_json_encode($log_entry) . PHP_EOL,
            3,
            $log_file
        );
    }

    private function rotate_logs() {
        $log_file = $this->log_directory . '/errors.log';
        $backup_file = $this->log_directory . '/errors.' . date('Y-m-d-H-i-s') . '.log';
        
        rename($log_file, $backup_file);
        
        // Clean up old log files (keep last 5)
        $files = glob($this->log_directory . '/errors.*.log');
        if (count($files) > 5) {
            array_map('unlink', array_slice($files, 0, -5));
        }
    }

    public function add_error_logs_page() {
        add_submenu_page(
            'tools.php',
            __('Theme Error Logs', '_themename'),
            __('Theme Error Logs', '_themename'),
            'manage_options',
            'theme-error-logs',
            array($this, 'render_error_logs_page')
        );
    }

    public function render_error_logs_page() {
        if (!current_user_can('manage_options')) {
            return;
        }

        $log_file = $this->log_directory . '/errors.log';
        $errors = array();

        if (file_exists($log_file)) {
            $lines = file($log_file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            foreach ($lines as $line) {
                $errors[] = json_decode($line, true);
            }
        }

        include get_template_directory() . '/template-parts/admin/error-logs.php';
    }
}

// Initialize error logging
add_action('init', array(ThemeErrorLogger::getInstance(), 'init'));