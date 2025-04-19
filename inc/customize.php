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

    // Banner setting
	$wp_customize->add_setting(
		'_themename_home_banner_media',
			[
			'capability'        => 'edit_theme_options',
			'default'           => esc_url(get_template_directory_uri() . '/src/assets/images/HomePageVideo3.mp4'),
			'sanitize_callback' => '_themename_sanitize_media',
			]
	);

	// Banner control - Use WP_Customize_Image_Control directly
	$wp_customize->add_control(
		new \WP_Customize_Image_Control(
			$wp_customize,
			'_themename_home_banner_media',
			[
			'label'       => __('Upload Home Banner', '_themename'),
			'description' => __('The Banner will be displayed in the Home Main Banner.', '_themename' ),
			'section'     => '_themename_home_banner',
			]
		)
	);

    // Register Banner Text setting.
	$wp_customize->add_setting(
		'_themename_home_banner_text',
		[
			'default'           => '',
			'sanitize_callback' => 'sanitize_text_field',
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
			'title'       => esc_html__( 'Social Media', '_themename' ),
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

    function _themename_sanitize_checkbox($checked) {
        return ( ( isset($checked) && $checked == true) ? true : false );
    }

    function _themename_show_single_blog_section(){
        global $post;
       return is_single() && $post->post_type === 'post';
    }
    
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
