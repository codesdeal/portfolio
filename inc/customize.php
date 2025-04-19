<?php
function devabu_customize_register($wp_customize) {
    $wp_customize->get_setting('blogname')->transport = 'postMessage';

    $wp_customize->selective_refresh->add_partial('blogname', array(
        'selector' => '.c-header__blogname',
        'container_inclusive' => false,
        'render_callback' => function() {
            bloginfo('name');
        }
    ));

    /*################## General Settings ###############*/
    $wp_customize->add_section('devabu_general_options', array(
        'title' => esc_html__('General Options', 'devabu'),
        'description' => esc_html__('You can change general options from here.', 'devabu')
    ));

    $wp_customize->add_setting('devabu_accent_color', array(
        'default' => '#20DDAE',
        'transport' => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ));

    $wp_customize->add_control(new \WP_Customize_Color_Control($wp_customize, 'devabu_accent_color', array(
        'label' => esc_html__('Accent Color', 'devabu'),
        'section' => 'devabu_general_options'
    )));

     /*################## Home Options ###############*/
    $wp_customize->add_section('devabu_home_banner', array(
        'title' => esc_html__('Home Banner Options', 'devabu'),
        'description' => esc_html__('You can change Home Banner from here.', 'devabu'),
        'priority' => 20
    ));

    // Banner setting
	$wp_customize->add_setting(
		'devabu_home_banner_media',
			[
			'capability'        => 'edit_theme_options',
			'default'           => esc_url(get_template_directory_uri() . '/src/assets/images/HomePageVideo3.mp4'),
			'sanitize_callback' => 'devabu_sanitize_media',
			]
	);

	// Banner control - Use WP_Customize_Image_Control directly
	$wp_customize->add_control(
		new \WP_Customize_Image_Control(
			$wp_customize,
			'devabu_home_banner_media',
			[
			'label'       => __('Upload Home Banner', 'devabu'),
			'description' => __('The Banner will be displayed in the Home Main Banner.', 'devabu' ),
			'section'     => 'devabu_home_banner',
			]
		)
	);

    // Register Banner Text setting.
	$wp_customize->add_setting(
		'devabu_home_banner_text',
		[
			'default'           => '',
			'sanitize_callback' => 'sanitize_text_field',
		]
	);

	// Create the Banner Text setting field.
	$wp_customize->add_control(
		'devabu_home_banner_text',
		[
			'label'       => esc_attr__( 'Add Banner Text', 'devabu' ),
			'description' => esc_attr__( 'The Banner Text will be displayed over Banner Image.', 'devabu' ),
			'section'     => 'devabu_home_banner',
			'type'        => 'textarea',
		]
	);

    // Register Banner links setting.
	$wp_customize->add_setting(
		'devabu_home_banner_links_html',
		[
			'default'           => '',
			'sanitize_callback' => 'devabu_sanitize_footer_info',
		]
	);

	// Create the Banner links textarea setting field.
	$wp_customize->add_control(
		'devabu_home_banner_links_html',
		[
			'label'       => esc_attr__( 'Add Banner link html', 'devabu' ),
			'description' => esc_attr__( 'The Banner Link will be displayed below Banner Image.', 'devabu' ),
			'section'     => 'devabu_home_banner',
			'type'        => 'textarea',
		]
	);

    // Register home company logo overview
	$wp_customize->add_setting(
		'devabu_home_company_logo_overview',
		[
			'default'           => '',
			'sanitize_callback' => 'devabu_sanitize_footer_info',
		]
	);

	// Create the Home Company Logo Overview setting field.
	$wp_customize->add_control(
		'devabu_home_company_logo_overview',
		[
			'label'       => esc_attr__( 'Add Home Company Logo Overview', 'devabu' ),
			'description' => esc_attr__( 'Home Company Logo Overview will be displayed over company logo.', 'devabu' ),
			'section'     => 'devabu_home_banner',
			'type'        => 'textarea',
		]
	);

    // Register home blog overview
	$wp_customize->add_setting(
		'devabu_home_blog_overview',
		[
			'default'           => '',
			'sanitize_callback' => 'devabu_sanitize_footer_info',
		]
	);

	// Create the home blog overview setting field.
	$wp_customize->add_control(
		'devabu_home_blog_overview',
		[
			'label'       => esc_attr__( 'Add Home Blog Overview', 'devabu' ),
			'description' => esc_attr__( 'Home Blog will be displayed over blog area.', 'devabu' ),
			'section'     => 'devabu_home_banner',
			'type'        => 'textarea',
		]
	);


    /*################## Footer Settings ###############*/
    $wp_customize->selective_refresh->add_partial('devabu_footer_partials', array(
        'settings' => array('devabu_footer_bg', 'devabu_footer_layout'),
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
		'devabu_social_links_section',
		[
			'title'       => esc_html__( 'Social Media', 'devabu' ),
			'description' => esc_html__( 'Links here power the Social Media', 'devabu' ),
			'priority'    => 30,
		]
	);

    $social_networks = [ 'facebook', 'x-twitter', 'instagram', 'youtube', 'linkedin' ];

	foreach ( $social_networks as $network ) {
		$wp_customize->add_setting(
			'devabu_' . $network . '_link',
			[
				'default'           => '',
				'sanitize_callback' => 'esc_url',
			]
		);

		$wp_customize->add_control(
			'devabu_' . $network . '_link',
			[
				'label'   => sprintf( esc_attr__( '%s URL', 'devabu' ), ucwords( $network ) ),
				'section' => 'devabu_social_links_section',
				'type'    => 'text',
			]
		);
	}

    // Footer columns
    $wp_customize->add_section('devabu_footer_column_1', array(
        'title'       => esc_html__('Footer Columns', 'devabu'),
        'description' => esc_html__('You can change footer options from here.', 'devabu'),
        'priority'    => 40
    ));
    
    for ( $i = 2; $i <= 4; $i++ ) {
    
        $wp_customize->add_setting( "devabu_footer_info_col_$i", array(
            'default'           => '',
            'sanitize_callback' => 'devabu_sanitize_footer_info',
            'transport'         => 'postMessage',
        ) );
    
        $wp_customize->add_control( "devabu_footer_info_col_$i", array(
            'type'    => 'textarea',
            'label'   => sprintf( esc_html__( 'Site Footer Info Column %d', 'devabu' ), $i ),
            'section' => 'devabu_footer_column_1',
        ) );
    }

    $wp_customize->add_setting('devabu_site_info', array(
        'default' => '',
        'sanitize_callback' => 'devabu_sanitize_footer_info',
        'transport' => 'postMessage'
    ));

    $wp_customize->add_control('devabu_site_info', array(
        'type' => 'textarea',
        'label' => esc_html__('Site Info', 'devabu'),
        'section' => 'devabu_footer_column_1'
    ));

    $wp_customize->add_setting('devabu_footer_bg', array(
        'default' => 'dark',
        'transport' => 'postMessage',
        'sanitize_callback' => 'devabu_sanitize_footer_bg',
    ));

    $wp_customize->add_control('devabu_footer_bg', array(
        'type' => 'select',
        'label' => esc_html__('Footer Background', 'devabu'),
        'section' => 'devabu_footer_column_1',
        'choices' => array(
            'light' => esc_html__('Light', 'devabu'),
            'dark' => esc_html__('Dark', 'devabu'),
        ),        
    ));

    $wp_customize->add_setting('devabu_footer_layout', array(
        'default' => '3,3,3,3',
        'transport' => 'postMessage',
        'sanitize_callback' => 'sanitize_text_field',
        'validate_callback' => 'devabu_validate_footer_layout'
    ));

    $wp_customize->add_control('devabu_footer_layout', array(
        'type' => 'text',
        'label' => esc_html__('Footer Layout', 'devabu'),
        'section' => 'devabu_footer_column_1'
    ));

    /*################## Single Post Settings ###############*/
    $wp_customize->add_section('devabu_single_blog_options', array(
        'title' => esc_html__('Single Blog Options', 'devabu'),
        'description' => esc_html__('You can change blog options from here.', 'devabu'),
        'active_callback' => 'devabu_show_single_blog_section'
    ));


    $wp_customize->add_setting('devabu_display_author_info', array(
        'default' => true,
        'transport' => 'postMessage',
        'sanitize_callback' => 'devabu_sanitize_checkbox'
    ));

    $wp_customize->add_control('devabu_display_author_info', array(
        'type' => 'checkbox',
        'label' => esc_html__('Display Author Info', 'devabu'),
        'section' => 'devabu_single_blog_options'
    ));

    function devabu_sanitize_checkbox($checked) {
        return ( ( isset($checked) && $checked == true) ? true : false );
    }

    function devabu_show_single_blog_section(){
        global $post;
       return is_single() && $post->post_type === 'post';
    }
    
}
add_action( 'customize_register', 'devabu_customize_register' );

function devabu_validate_footer_layout( $validity, $value) {
    if(!preg_match('/^([1-9]|1[012])(,([1-9]|1[012]))*$/', $value)) {
        $validity->add('invalid_footer_layout', esc_html__( 'Footer layout is invalid', 'devabu' ));
    }
    return $validity;
}

function devabu_sanitize_footer_bg($input) {
    $valid = array('light', 'dark');
    if(in_array($input, $valid, true)) {
        return $input;
    }
    return 'dark';
}

function devabu_sanitize_footer_info( $input ) {
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
		'p'    => [ 'class' => [] ],
		'div'  => [ 'class' => [], 'id' => [] ],
		'span' => [ 'class' => [] ],
		'ul'   => [ 'class' => [] ],
		'li'   => [ 'class' => [] ],
		'br'   => [],
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
