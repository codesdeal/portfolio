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

<nav class="navbar bg-body-tertiary fixed-top">
    <div class="container">
        <a class="navbar-brand" href="<?php echo esc_url(home_url('/')); ?>">
            <?php
                if (has_custom_logo()) {
                    the_custom_logo();
                } else {
                    bloginfo('name');
                }
            ?>
        </a>
        
        <!-- Desktop Menu -->
        <div class="desktop-menu d-none d-lg-block">
            <?php
            if (has_nav_menu('main-menu')) {
                wp_nav_menu(array(
                    'theme_location' => 'main-menu',
                    'menu_class'     => 'navbar-nav d-flex flex-row gap-4',
                    'container'      => false,
                    'walker'         => new _themename_Mega_Menu_Walker(),
                    'items_wrap'     => '<ul id="%1$s" class="%2$s">%3$s</ul>'
                ));
            }
            ?>
        </div>

        <!-- Mobile Menu Toggle -->
        <button class="navbar-toggler d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Mobile Offcanvas Menu -->
        <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title" id="offcanvasNavbarLabel"><?php bloginfo('name'); ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                <?php
                if (has_nav_menu('main-menu')) {
                    wp_nav_menu(array(
                        'theme_location' => 'main-menu',
                        'menu_class'     => 'navbar-nav justify-content-end flex-grow-1 pe-3',
                        'container'      => false,
                        'walker'         => new _themename_Mega_Menu_Walker(),
                        'items_wrap'     => '<ul id="%1$s" class="%2$s">%3$s</ul>'
                    ));
                }
                ?>
            </div>
        </div>
    </div>
</nav>

<div id="page" class="site">
    <div id="content" class="site-content" role="main">