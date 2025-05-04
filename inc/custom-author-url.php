<?php
/**
 * Custom Author URL based on Nickname
 *
 * This code snippet modifies the author URL structure to use the user's nickname instead of the default author ID.
 * It also handles redirection from the old author URL to the new one.
 *
 * @package _themename
 */

function nickname_author_rewrite_rules($wp_rewrite) {
    $new_rules = [];
    $users = get_users();

    foreach ($users as $user) {
        $nickname = sanitize_title($user->nickname);
        if ($nickname) {
            $new_rules['profile/' . $nickname . '/?$'] = 'index.php?author=' . $user->ID;
        }
    }

    $wp_rewrite->rules = $new_rules + $wp_rewrite->rules;
}
add_filter('generate_rewrite_rules', 'nickname_author_rewrite_rules');


function redirect_author_to_nickname_url() {
    if (is_author()) {
        $user_id = get_queried_object_id();
        $nickname = get_the_author_meta('nickname', $user_id);

        if ($nickname) {
            $nickname_slug = sanitize_title($nickname);
            $desired_url = home_url('/profile/' . $nickname_slug . '/');
            if (trailingslashit($_SERVER['REQUEST_URI']) !== trailingslashit(parse_url($desired_url, PHP_URL_PATH))) {
                wp_redirect($desired_url, 301);
                exit;
            }
        }
    }
}
add_action('template_redirect', 'redirect_author_to_nickname_url');
