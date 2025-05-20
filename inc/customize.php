<?php
function _themename_customize_register($wp_customize) {
    $wp_customize->get_setting('blogname')->transport = 'postMessage';

    $wp_customize->selective_refresh->add_partial('blogname', array(
        'selector' => '.c-header__blogname',
        'container_inclusive' => false,
        'render_callback' => function() {
            bloginfo('name');
        }
    ));

    /*################## General Settings ###############*/
    
   $wp_customize->add_section('_themename_general_options', array(
        'title' => esc_html__('General Options', '_themename'),
        'description' => esc_html__('You can change general options from here.', '_themename')
    ));

    $wp_customize->add_setting('_themename_accent_color', array(
        'default' => '#20DDAE',
        'transport' => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ));

    $wp_customize->add_control(new \WP_Customize_Color_Control($wp_customize, '_themename_accent_color', array(
        'label' => esc_html__('Accent Color', '_themename'),
        'section' => '_themename_general_options'
    )));

     /*################## Home Options ###############*/
    $wp_customize->add_section('_themename_home_banner', array(
        'title' => esc_html__('Home Banner Options', '_themename'),
        'description' => esc_html__('You can change Home Banner from here.', '_themename'),
        'priority' => 20
    ));

    // Banner video setting
	$wp_customize->add_setting(
		'_themename_home_banner_media',
			[
			'capability'        => 'edit_theme_options',
			'default'           => '',
			'sanitize_callback' => '_themename_sanitize_media',
			]
	);

	// Banner video control
	$wp_customize->add_control(
		new \WP_Customize_Media_Control(
			$wp_customize,
			'_themename_home_banner_media',
			[
			'label'       => __('Upload Home Banner Video', '_themename'),
			'description' => __('The video will be displayed in the Home Main Banner.', '_themename' ),
			'section'     => '_themename_home_banner',
			'mime_type'   => 'video',
			]
		)
	);

    // Banner fallback image setting
    $wp_customize->add_setting(
        '_themename_home_banner_image',
        array(
            'default'           => '',
            'capability'        => 'edit_theme_options',
            'transport'         => 'refresh',
            'sanitize_callback' => 'absint'
        )
    );

    // Banner fallback image control
    $wp_customize->add_control(
        new WP_Customize_Media_Control(
            $wp_customize,
            '_themename_home_banner_image',
            array(
                'label'       => __('Upload Fallback Image', '_themename'),
                'description' => __('This image will be displayed when no video is set.', '_themename'),
                'section'     => '_themename_home_banner',
                'mime_type'   => 'image',
                'button_labels' => array(
                    'select'       => __('Select Image', '_themename'),
                    'change'       => __('Change Image', '_themename'),
                    'remove'       => __('Remove', '_themename'),
                    'default'      => __('Default', '_themename'),
                    'placeholder'  => __('No image selected', '_themename'),
                    'frame_title'  => __('Select Image', '_themename'),
                    'frame_button' => __('Choose Image', '_themename'),
                )
            )
        )
    );

    // Mobile fallback image setting
    $wp_customize->add_setting(
        '_themename_home_banner_image_mobile',
        array(
            'default'           => '',
            'capability'        => 'edit_theme_options',
            'transport'         => 'refresh',
            'sanitize_callback' => 'absint'
        )
    );

    // Mobile fallback image control
    $wp_customize->add_control(
        new WP_Customize_Media_Control(
            $wp_customize,
            '_themename_home_banner_image_mobile',
            array(
                'label'       => __('Upload Mobile Fallback Image', '_themename'),
                'description' => __('This image will be displayed on mobile devices when no video is set.', '_themename'),
                'section'     => '_themename_home_banner',
                'mime_type'   => 'image',
                'button_labels' => array(
                    'select'       => __('Select Image', '_themename'),
                    'change'       => __('Change Image', '_themename'),
                    'remove'       => __('Remove', '_themename'),
                    'default'      => __('Default', '_themename'),
                    'placeholder'  => __('No image selected', '_themename'),
                    'frame_title'  => __('Select Image', '_themename'),
                    'frame_button' => __('Choose Image', '_themename'),
                )
            )
        )
    );


    // Register Banner Text setting.
	$wp_customize->add_setting(
		'_themename_home_banner_text',
		[
			'default'           => '',
			'sanitize_callback' => '_themename_sanitize_footer_info',
		]
	);

	// Create the Banner Text setting field.
	$wp_customize->add_control(
		'_themename_home_banner_text',
		[
			'label'       => esc_attr__( 'Add Banner Text', '_themename' ),
			'description' => esc_attr__( 'The Banner Text will be displayed over Banner Image.', '_themename' ),
			'section'     => '_themename_home_banner',
			'type'        => 'textarea',
		]
	);

    // Register Banner links setting.
	$wp_customize->add_setting(
		'_themename_home_banner_links_html',
		[
			'default'           => '',
			'sanitize_callback' => '_themename_sanitize_footer_info',
		]
	);

	// Create the Banner links textarea setting field.
	$wp_customize->add_control(
		'_themename_home_banner_links_html',
		[
			'label'       => esc_attr__( 'Add Banner link html', '_themename' ),
			'description' => esc_attr__( 'The Banner Link will be displayed below Banner Image.', '_themename' ),
			'section'     => '_themename_home_banner',
			'type'        => 'textarea',
		]
	);

    // Register home company logo overview
	$wp_customize->add_setting(
		'_themename_home_company_logo_overview',
		[
			'default'           => '',
			'sanitize_callback' => '_themename_sanitize_footer_info',
		]
	);

	// Create the Home Company Logo Overview setting field.
	$wp_customize->add_control(
		'_themename_home_company_logo_overview',
		[
			'label'       => esc_attr__( 'Add Home Company Logo Overview', '_themename' ),
			'description' => esc_attr__( 'Home Company Logo Overview will be displayed over company logo.', '_themename' ),
			'section'     => '_themename_home_banner',
			'type'        => 'textarea',
		]
	);

    // Register home blog overview
	$wp_customize->add_setting(
		'_themename_home_blog_overview',
		[
			'default'           => '',
			'sanitize_callback' => '_themename_sanitize_footer_info',
		]
	);

	// Create the home blog overview setting field.
	$wp_customize->add_control(
		'_themename_home_blog_overview',
		[
			'label'       => esc_attr__( 'Add Home Blog Overview', '_themename' ),
			'description' => esc_attr__( 'Home Blog will be displayed over blog area.', '_themename' ),
			'section'     => '_themename_home_banner',
			'type'        => 'textarea',
		]
	);

    /*################## Testimonials Settings ###############*/
    $wp_customize->add_section('_themename_testimonials_options', array(
        'title' => esc_html__('Testimonials Options', '_themename'),
        'description' => esc_html__('You can change testimonials section options from here.', '_themename'),
        'priority' => 25
    ));

    // Register testimonials overview text
    $wp_customize->add_setting(
        '_themename_testimonials_overview',
        [
            'default'           => '',
            'sanitize_callback' => '_themename_sanitize_footer_info',
        ]
    );

    // Create the testimonials overview setting field
    $wp_customize->add_control(
        '_themename_testimonials_overview',
        [
            'label'       => esc_attr__('Testimonials Section Overview', '_themename'),
            'description' => esc_attr__('Add heading and description for the testimonials section.', '_themename'),
            'section'     => '_themename_testimonials_options',
            'type'        => 'textarea',
        ]
    );

    /*################## Footer Settings ###############*/
    $wp_customize->selective_refresh->add_partial('_themename_footer_partials', array(
        'settings' => array('_themename_footer_bg', '_themename_footer_layout'),
        'selector' => '#footer',
        'container_inclusive' => false,
        'render_callback' => function() {
            get_template_part('template-parts/footer/top-info');
            get_template_part('template-parts/footer/widgets');
            get_template_part('template-parts/footer/info');
            
        }
    ));

    // Footer Social Media Links
	$wp_customize->add_section(
		'_themename_social_links_section',
		[
			'title'       => esc_html__( 'Social URL & Footer Logo', '_themename' ),
			'description' => esc_html__( 'Links here power the Social Media', '_themename' ),
			'priority'    => 30,
		]
	);

    $social_networks = [ 'facebook', 'x-twitter', 'instagram', 'youtube', 'linkedin' ];

	foreach ( $social_networks as $network ) {
		$wp_customize->add_setting(
			'_themename_' . $network . '_link',
			[
				'default'           => '',
				'sanitize_callback' => 'esc_url',
			]
		);

		$wp_customize->add_control(
			'_themename_' . $network . '_link',
			[
				'label'   => sprintf( esc_attr__( '%s URL', '_themename' ), ucwords( $network ) ),
				'section' => '_themename_social_links_section',
				'type'    => 'text',
			]
		);
	}

    // Footer Logo setting
    $wp_customize->add_setting(
        '_themename_footer_logo',
            [
            'capability'        => 'edit_theme_options',
            'default'           => esc_url(get_template_directory_uri() . '/src/images/icon-192x192.png'),
            'sanitize_callback' => '_themename_sanitize_media',
            ]
    );

    // Footer Logo control - Use WP_Customize_Image_Control directly
    $wp_customize->add_control(
        new \WP_Customize_Image_Control(
            $wp_customize,
            '_themename_footer_logo',
            [
            'label'       => __('Upload Footer Logo', '_themename'),
            'description' => __('The Footer Logo will be displayed in the footer.', '_themename' ),
            'section'     => '_themename_social_links_section',
            ]
        )
    );

    // Footer columns
    $wp_customize->add_section('_themename_footer_column_1', array(
        'title'       => esc_html__('Footer Columns', '_themename'),
        'description' => esc_html__('You can change footer options from here.', '_themename'),
        'priority'    => 40
    ));
    
    for ( $i = 2; $i <= 4; $i++ ) {
    
        $wp_customize->add_setting( "_themename_footer_info_col_$i", array(
            'default'           => '',
            'sanitize_callback' => '_themename_sanitize_footer_info',
            'transport'         => 'postMessage',
        ) );
    
        $wp_customize->add_control( "_themename_footer_info_col_$i", array(
            'type'    => 'textarea',
            'label'   => sprintf( esc_html__( 'Site Footer Info Column %d', '_themename' ), $i ),
            'section' => '_themename_footer_column_1',
        ) );
    }

    $wp_customize->add_setting('_themename_site_info', array(
        'default' => '',
        'sanitize_callback' => '_themename_sanitize_footer_info',
        'transport' => 'postMessage'
    ));

    $wp_customize->add_control('_themename_site_info', array(
        'type' => 'textarea',
        'label' => esc_html__('Site Info', '_themename'),
        'section' => '_themename_footer_column_1'
    ));

    $wp_customize->add_setting('_themename_footer_bg', array(
        'default' => 'dark',
        'transport' => 'postMessage',
        'sanitize_callback' => '_themename_sanitize_footer_bg',
    ));

    $wp_customize->add_control('_themename_footer_bg', array(
        'type' => 'select',
        'label' => esc_html__('Footer Background', '_themename'),
        'section' => '_themename_footer_column_1',
        'choices' => array(
            'light' => esc_html__('Light', '_themename'),
            'dark' => esc_html__('Dark', '_themename'),
        ),        
    ));

    $wp_customize->add_setting('_themename_footer_layout', array(
        'default' => '3,3,3,3',
        'transport' => 'postMessage',
        'sanitize_callback' => 'sanitize_text_field',
        'validate_callback' => '_themename_validate_footer_layout'
    ));

    $wp_customize->add_control('_themename_footer_layout', array(
        'type' => 'text',
        'label' => esc_html__('Footer Layout', '_themename'),
        'section' => '_themename_footer_column_1'
    ));

    /*################## Single Post Settings ###############*/
    $wp_customize->add_section('_themename_single_blog_options', array(
        'title' => esc_html__('Single Blog Options', '_themename'),
        'description' => esc_html__('You can change blog options from here.', '_themename'),
        'active_callback' => '_themename_show_single_blog_section'
    ));


    $wp_customize->add_setting('_themename_display_author_info', array(
        'default' => true,
        'transport' => 'postMessage',
        'sanitize_callback' => '_themename_sanitize_checkbox'
    ));

    $wp_customize->add_control('_themename_display_author_info', array(
        'type' => 'checkbox',
        'label' => esc_html__('Display Author Info', '_themename'),
        'section' => '_themename_single_blog_options'
    ));

    /*################## Push Notifications Settings ###############*/
    $wp_customize->add_section('_themename_push_notifications', array(
        'title' => __('Push Notifications', '_themename'),
        'description' => __('Configure push notification settings', '_themename'),
        'priority' => 160
    ));

    // Enable/Disable Push Notifications
    $wp_customize->add_setting('_themename_enable_push_notifications', array(
        'default' => true,
        'sanitize_callback' => 'wp_validate_boolean'
    ));

    $wp_customize->add_control('_themename_enable_push_notifications', array(
        'type' => 'checkbox',
        'section' => '_themename_push_notifications',
        'label' => __('Enable Push Notifications', '_themename'),
        'description' => __('Allow users to subscribe to push notifications', '_themename')
    ));

    // Notification Types
    $wp_customize->add_setting('_themename_notification_types', array(
        'default' => array('posts', 'comments'),
        'sanitize_callback' => function($input) {
            return is_array($input) ? array_map('sanitize_text_field', $input) : array();
        }
    ));

    $wp_customize->add_control(new WP_Customize_Control(
        $wp_customize,
        '_themename_notification_types',
        array(
            'type' => 'select',
            'section' => '_themename_push_notifications',
            'label' => __('Send Notifications For', '_themename'),
            'description' => __('Choose which events trigger push notifications', '_themename'),
            'choices' => array(
                'posts' => __('New Posts', '_themename'),
                'comments' => __('New Comments', '_themename'),
                'products' => __('New Products', '_themename')
            ),
            'multiple' => true
        )
    ));

    // Notification Prompt Text
    $wp_customize->add_setting('_themename_notification_prompt', array(
        'default' => __('Would you like to receive notifications for new content?', '_themename'),
        'sanitize_callback' => 'sanitize_text_field'
    ));

    $wp_customize->add_control('_themename_notification_prompt', array(
        'type' => 'text',
        'section' => '_themename_push_notifications',
        'label' => __('Notification Prompt', '_themename'),
        'description' => __('Text shown when asking users to enable notifications', '_themename')
    ));

    function _themename_sanitize_checkbox($checked) {
        return ( ( isset($checked) && $checked == true) ? true : false );
    }

    function _themename_show_single_blog_section(){
        global $post;
       return is_single() && $post->post_type === 'post';
    }

    // ############ Achievements section Start ############
    $wp_customize->add_section('_themename_achievements', [
        'title' => __('Counter Sections', '_themename'),
        'priority' => 60,
        'description' => __('Customize the Counter Sections', '_themename'),
    ]);

    $wp_customize->add_setting('_themename_achievements_activate', [
        'default' => true, // Default is to show the section
        'sanitize_callback' => '_themename_sanitize_checkbox',
    ]);
    
    $wp_customize->add_control('_themename_achievements_activate', [
        'label' => __('Activate Achievements Section', '_themename'),
        'section' => '_themename_achievements',
        'type' => 'checkbox',
    ]);

    $wp_customize->add_setting('_themename_achievment_title', [
        'default' => 'MY ACHIEVEMENTS',
        'sanitize_callback' => 'wp_kses_post',
    ]);

    $wp_customize->add_control('_themename_achievment_title', [
        'label' => __('Achievement Section Title', '_themename'),
        'section' => '_themename_achievements',
        'type' => 'text',
    ]);

    // Setting for number of counters
    $wp_customize->add_setting('_themename_num_achievements', [
        'default' => '3',
        'sanitize_callback' => 'absint',
        'transport' => 'refresh',
    ]);

    $wp_customize->add_control('_themename_num_achievements', [
        'label' => __('Number of Counters', '_themename'),
        'section' => '_themename_achievements',
        'type' => 'number',
    ]);

    $num_achievements = get_theme_mod('_themename_num_achievements', 4);

    // Add settings and controls for each counter
    for ($i = 1; $i <= $num_achievements; $i++) {
        $wp_customize->add_setting("_themename_counter_number_$i", [
            'default' => '5454',
            'sanitize_callback' => 'absint',
        ]);
        $wp_customize->add_control("_themename_counter_number_$i", [
            'label' => __("Counter $i Number", '_themename'),
            'section' => '_themename_achievements',
            'type' => 'number',
        ]);

        $wp_customize->add_setting("_themename_counter_title_$i", [
            'default' => 'articles',
            'sanitize_callback' => 'wp_kses_post',
        ]);
        $wp_customize->add_control("_themename_counter_title_$i", [
            'label' => __("Counter $i Title", '_themename'),
            'section' => '_themename_achievements',
            'type' => 'text',
        ]);

        $wp_customize->add_setting("_themename_counter_description_$i", [
            'default' => 'Habitasse platea dictumst. Ut tellus sem, suscipit ut enim id.',
            'sanitize_callback' => 'wp_kses_post',
        ]);
        $wp_customize->add_control("_themename_counter_description_$i", [
            'label' => __("Counter $i Description", '_themename'),
            'section' => '_themename_achievements',
            'type' => 'textarea',
        ]);
    }  
    // ############ Achievements section End ############
    
}
add_action( 'customize_register', '_themename_customize_register' );

function _themename_validate_footer_layout( $validity, $value) {
    if(!preg_match('/^([1-9]|1[012])(,([1-9]|1[012]))*$/', $value)) {
        $validity->add('invalid_footer_layout', esc_html__( 'Footer layout is invalid', '_themename' ));
    }
    return $validity;
}

function _themename_sanitize_footer_bg($input) {
    $valid = array('light', 'dark');
    if(in_array($input, $valid, true)) {
        return $input;
    }
    return 'dark';
}

function _themename_sanitize_footer_info( $input ) {
	if ( ! is_string( $input ) ) {
		return '';
	}

	$allowed_tags = [
		'h1'   => [ 'class' => [] ],
		'h2'   => [ 'class' => [] ],
		'h3'   => [ 'class' => [] ],
		'h4'   => [ 'class' => [] ],
		'h5'   => [ 'class' => [] ],
		'h6'   => [ 'class' => [] ],
        'quote' => [ 'class' => [] ],
		'p'    => [ 'class' => [] ],
		'div'  => [ 'class' => [], 'id' => [] ],
		'span' => [ 'class' => [] ],
		'ul'   => [ 'class' => [] ],
		'li'   => [ 'class' => [] ],
		'br'   => [],
        'b'   => [],
        'strong' => [],
		'i'    => [ 'class' => [] ],
		'a'    => [
			'href'   => [],
			'title'  => [],
			'target' => [],
			'class'  => [],
		],
	];

	return wp_kses( $input, $allowed_tags );
}
