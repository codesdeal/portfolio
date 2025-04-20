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

if(!function_exists('_themename_verify_security')) {
    function _themename_verify_security($nonce_name, $action_name, $redirect_on_fail = true) {
        if (
            !isset($_REQUEST['_wpnonce']) 
            || !wp_verify_nonce($_REQUEST['_wpnonce'], $nonce_name)
            || !current_user_can('edit_posts')
        ) {
            if ($redirect_on_fail) {
                wp_safe_redirect(home_url());
                exit;
            }
            return false;
        }
        return true;
    }
}

if (!function_exists('_themename_responsive_image')) {
    function _themename_responsive_image($image_id, $size = 'full', $additional_attributes = []) {
        if (!$image_id) return;

        $default_attributes = [
            'loading' => 'lazy',
            'decoding' => 'async'
        ];

        $attributes = array_merge($default_attributes, $additional_attributes);
        
        $img_src = wp_get_attachment_image_url($image_id, $size);
        $img_srcset = wp_get_attachment_image_srcset($image_id, $size);
        $img_sizes = wp_get_attachment_image_sizes($image_id, $size);
        $alt = get_post_meta($image_id, '_wp_attachment_image_alt', true);

        return sprintf(
            '<img src="%s" srcset="%s" sizes="%s" alt="%s" %s>',
            esc_url($img_src),
            esc_attr($img_srcset),
            esc_attr($img_sizes),
            esc_attr($alt),
            _themename_prepare_html_attributes($attributes)
        );
    }
}

if (!function_exists('_themename_prepare_html_attributes')) {
    function _themename_prepare_html_attributes($attributes) {
        $html = '';
        foreach ($attributes as $name => $value) {
            $html .= sprintf(' %s="%s"', esc_attr($name), esc_attr($value));
        }
        return $html;
    }
}

