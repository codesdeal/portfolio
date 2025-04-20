<?php
/**
 * Registers a custom post type for Testimonials.
 * @package _themename
 */
function _themename_post_type_testimonial() {
    $labels = array(
        'name'               => __('Testimonials', '_themename'),
        'singular_name'      => __('Testimonial', '_themename'),
        'menu_name'          => __('Testimonials', '_themename'),
        'all_items'          => __('All Testimonials', '_themename'),
        'add_new'            => __('Add New Testimonial', '_themename'),
        'add_new_item'       => __('Add New Testimonial', '_themename'),
        'edit_item'          => __('Edit Testimonial', '_themename'),
        'new_item'           => __('New Testimonial', '_themename'),
        'view_item'          => __('View Testimonial', '_themename'),
        'not_found'          => __('No testimonials found', '_themename'),
    );

    $args = array(
        'labels'              => $labels,
        'menu_icon'          => 'dashicons-format-quote',
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'show_in_rest'       => true,
        'query_var'          => true,
        'rewrite'            => array('slug' => 'testimonial'),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => 7,
        'supports'           => array('title', 'editor', 'thumbnail'),
    );

    register_post_type('testimonial', $args);
}
add_action('init', '_themename_post_type_testimonial');

/**
 * Add meta box for testimonial position
 */
function _themename_add_testimonial_meta_boxes() {
    add_meta_box(
        '_themename_testimonial_position',
        __('Position/Role', '_themename'),
        '_themename_testimonial_position_callback',
        'testimonial',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', '_themename_add_testimonial_meta_boxes');

/**
 * Meta box callback function
 */
function _themename_testimonial_position_callback($post) {
    wp_nonce_field('_themename_save_testimonial_position', '_themename_testimonial_position_nonce');
    $value = get_post_meta($post->ID, '_testimonial_position', true);
    ?>
    <p>
        <label for="testimonial_position"><?php _e('Enter the position/role of the person giving the testimonial', '_themename'); ?></label>
        <br />
        <input type="text" id="testimonial_position" name="testimonial_position" value="<?php echo esc_attr($value); ?>" size="50" />
    </p>
    <?php
}

/**
 * Save meta box content
 */
function _themename_save_testimonial_position($post_id) {
    if (!isset($_POST['_themename_testimonial_position_nonce'])) {
        return;
    }

    if (!wp_verify_nonce($_POST['_themename_testimonial_position_nonce'], '_themename_save_testimonial_position')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    if (!isset($_POST['testimonial_position'])) {
        return;
    }

    $position = sanitize_text_field($_POST['testimonial_position']);
    update_post_meta($post_id, '_testimonial_position', $position);
}
add_action('save_post_testimonial', '_themename_save_testimonial_position');