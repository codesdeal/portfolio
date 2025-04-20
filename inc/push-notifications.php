<?php
/**
 * Handle Web Push Notifications
 */

use Minishlink\WebPush\WebPush;
use Minishlink\WebPush\Subscription;

class _Themename_Push_Notifications {
    private $vapid_keys;
    private $webpush;

    public function __construct() {
        $this->init_vapid_keys();
        $this->init_web_push();
        $this->register_routes();
        $this->register_hooks();
    }

    private function init_vapid_keys() {
        $this->vapid_keys = get_option('_themename_vapid_keys');
        
        if (!$this->vapid_keys) {
            $this->generate_vapid_keys();
        }
    }

    private function generate_vapid_keys() {
        if (!function_exists('openssl_pkey_new')) {
            return false;
        }

        $res = openssl_pkey_new([
            "digest_alg" => "sha256",
            "private_key_bits" => 2048,
            "private_key_type" => OPENSSL_KEYTYPE_EC,
            "curve_name" => "prime256v1"
        ]);

        openssl_pkey_export($res, $private_key);
        $public_key = openssl_pkey_get_details($res)['key'];

        $this->vapid_keys = [
            'public' => base64_encode($public_key),
            'private' => base64_encode($private_key)
        ];

        update_option('_themename_vapid_keys', $this->vapid_keys);
    }

    private function init_web_push() {
        if (!class_exists('Minishlink\WebPush\WebPush')) {
            return;
        }

        $auth = [
            'VAPID' => [
                'subject' => get_bloginfo('url'),
                'publicKey' => $this->vapid_keys['public'],
                'privateKey' => $this->vapid_keys['private']
            ]
        ];

        $this->webpush = new WebPush($auth);
    }

    public function register_routes() {
        add_action('rest_api_init', function() {
            register_rest_route('portfolio/v1', '/vapid-key', [
                'methods' => 'GET',
                'callback' => [$this, 'get_vapid_key'],
                'permission_callback' => '__return_true'
            ]);

            register_rest_route('portfolio/v1', '/push-subscription', [
                'methods' => ['POST', 'DELETE'],
                'callback' => [$this, 'handle_subscription'],
                'permission_callback' => [$this, 'verify_subscription_request']
            ]);
        });
    }

    public function register_hooks() {
        // Send notifications when posts are published
        add_action('transition_post_status', [$this, 'notify_new_post'], 10, 3);
    }

    public function get_vapid_key() {
        return rest_ensure_response($this->vapid_keys['public']);
    }

    public function verify_subscription_request() {
        return is_user_logged_in() || wp_verify_nonce($_SERVER['HTTP_X_WP_NONCE'], 'wp_rest');
    }

    public function handle_subscription($request) {
        $subscription_data = json_decode($request->get_body(), true);
        $subscriptions = get_option('_themename_push_subscriptions', []);

        if ($request->get_method() === 'POST') {
            $subscriptions[] = $subscription_data;
            update_option('_themename_push_subscriptions', array_unique($subscriptions, SORT_REGULAR));
            return rest_ensure_response(['status' => 'subscribed']);
        }

        if ($request->get_method() === 'DELETE') {
            $subscriptions = array_filter($subscriptions, function($sub) use ($subscription_data) {
                return $sub['endpoint'] !== $subscription_data['endpoint'];
            });
            update_option('_themename_push_subscriptions', $subscriptions);
            return rest_ensure_response(['status' => 'unsubscribed']);
        }
    }

    public function notify_new_post($new_status, $old_status, $post) {
        if ($new_status === 'publish' && $old_status !== 'publish' && $post->post_type === 'post') {
            $subscriptions = get_option('_themename_push_subscriptions', []);
            
            if (empty($subscriptions)) {
                return;
            }

            $notification = [
                'title' => $post->post_title,
                'body' => wp_trim_words($post->post_content, 20),
                'icon' => get_site_icon_url(),
                'url' => get_permalink($post)
            ];

            foreach ($subscriptions as $subscription) {
                try {
                    $this->webpush->sendNotification(
                        Subscription::create($subscription),
                        json_encode($notification)
                    );
                } catch (Exception $e) {
                    error_log('Push notification error: ' . $e->getMessage());
                }
            }

            // Remove failed subscriptions
            foreach ($this->webpush->flush() as $report) {
                if (!$report->isSuccess()) {
                    $subscriptions = array_filter($subscriptions, function($sub) use ($report) {
                        return $sub['endpoint'] !== $report->getRequest()->getUri()->__toString();
                    });
                }
            }

            update_option('_themename_push_subscriptions', $subscriptions);
        }
    }
}

// Initialize push notifications
new _Themename_Push_Notifications();