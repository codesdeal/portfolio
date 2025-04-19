<?php

if(!function_exists('_themename_post_meta')) {
    function _themename_post_meta() {

        printf(
            esc_html__('Posted on %s', '_themename'),
            '<a href="' . esc_url(get_permalink()) . '"><time datetime="' . esc_attr(get_the_date('c')) . '">' . esc_html(get_the_date()) . '</time></a>'
        );

        printf(
            esc_html__(' By %s', '_themename'),
            '<a href="' . esc_url(get_author_posts_url(get_the_author_meta('ID'))) . '">' . esc_html(get_the_author()) . '</a>'
        );
    }
}

if(!function_exists('_themename_readmore_link')){
    function _themename_readmore_link() {
        echo '<a class="c-post__readmore" href="' . esc_url(get_permalink()) . '" title="' . the_title_attribute(['echo' => false]) . '">';
        printf(
            wp_kses(
                __('Read more <span class="u-screen-reader-text">About %s</span>', '_themename'),
                [
                    'span' => [
                        'class' => []
                    ]
                ]
            ),
            get_the_title()
        );
        echo '</a>';
    }
}

if(!function_exists('_themename_delete_post')){
    function _themename_delete_post() {
        $url = add_query_arg([
            'action' => '_themename_delete_post',
            'post' => get_the_ID(),
            'nonce' => wp_create_nonce('_themename_delete_post_nonce')
        ], home_url());

        if(current_user_can('delete_post', get_the_ID())) {
            return '<a href="' . esc_url($url) . '">' . esc_html__('Delete Post', '_themename') . '</a>';
        } else {
            return '';
        }
    }
}
if(!function_exists('_themename_handle_delete_post')){
    function _themename_handle_delete_post() {
        if (isset($_GET['action']) && $_GET['action'] === '_themename_delete_post') {
            if(!isset($_GET['nonce']) || !wp_verify_nonce($_GET['nonce'], '_themename_delete_post_nonce')) {
                return;
            }

            $post_id = isset($_GET['post']) ? (int)$_GET['post'] : null;
            $post = get_post((int)$post_id);
            if (empty($post)) {
                return;
            }

            if (!current_user_can('delete_posts')) {
                return;
            }

            wp_trash_post($post_id);
            wp_safe_redirect(home_url());
            exit;
        }
    }
}
add_action('init', '_themename_handle_delete_post');

if(!function_exists('_themename_meta')) {
    function _themename_meta($post_id, $meta_key) {
        $meta = get_post_meta($post_id, $meta_key, true);
        if($meta) {
            return $meta;
        } else {
            return '';
        }
    }
}

if ( ! function_exists( '_themename_sanitize_media' ) ) {
	function _themename_sanitize_media( $file, $setting ) {

		$mimes = array(
			// Images
			'jpg|jpeg|jpe' => 'image/jpeg',
			'gif'          => 'image/gif',
			'png'          => 'image/png',
			'bmp'          => 'image/bmp',
			'tif|tiff'     => 'image/tiff',
			'ico'          => 'image/x-icon',

			// Videos
			'mp4'          => 'video/mp4',
			'webm'         => 'video/webm',
			'ogg|ogv'      => 'video/ogg',
		);

		$file_ext = wp_check_filetype( $file, $mimes );

		return ( $file_ext['ext'] ? $file : $setting->default );
	}
}

