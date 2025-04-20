<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php echo esc_attr(_themename_get_meta_description()); ?>">
    
    <!-- PWA Support -->
    <link rel="manifest" href="<?php echo esc_url(get_template_directory_uri() . '/dist/assets/manifest.json'); ?>">
    <meta name="theme-color" content="#0066cc">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="<?php bloginfo('name'); ?>">
    <link rel="apple-touch-icon" href="<?php echo esc_url(get_template_directory_uri() . '/dist/assets/images/icon-192x192.png'); ?>">
    
    <!-- Preconnect to external domains -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    
    <!-- Open Graph and Twitter Card Tags -->
    <?php _themename_output_og_tags(); ?>
    
    <!-- Schema.org Markup -->
    <?php _themename_output_schema_markup('website'); ?>
    
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="u-skip-link" href="#content"><?php esc_attr_e('Skip to content', '_themename'); ?></a>
<header role="banner">
    <div class="c-header">
        <div class="o-container u-flex u-align-justify u-align-middle">
            <div class="c-header__logo">
                <?php if(has_custom_logo()) {
                    the_custom_logo();
                } else { ?>
                    <a class="c-header__blogname" href="<?php echo esc_url(home_url('/')); ?>">
                        <span class="screen-reader-text"><?php esc_html_e('Home', '_themename'); ?></span>
                        <?php esc_html(bloginfo('name')); ?>
                    </a>
                <?php } ?>
            </div>
            <div class="c-navigation">
            <nav class="header-nav" role="navigation" aria-label="<?php esc_html_e( 'Main Navigation', '_themename' ) ?>">
                <?php wp_nav_menu( array('theme_location' => 'main-menu') ) ?>
            </nav>
            </div>
        </div>
    </div>
</header>
<div id="content" class="site-content" role="main">