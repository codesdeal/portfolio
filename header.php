<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?> >
<a class="u-skip-link" href="#content"><?php esc_attr_e( 'Skip to content', 'devabu' ); ?></a>
<header role="banner">
    <div class="c-header">
        <div class="o-container u-flex u-align-justify u-align-middle">
            <div class="c-header__logo">
                <?php if(has_custom_logo( )) {
                    the_custom_logo();
                } else { ?>
                    <a class="c-header__blogname" href="<?php echo esc_url(home_url( '/' )); ?>"><?php esc_html(bloginfo( 'name' )); ?></a>
                <?php } ?>
            </div>
            <div class="c-navigation">
                <nav class="header-nav" role="navigation" aria-label="<?php esc_html_e( 'Main Navigation', 'devabu' ) ?>">
                    <?php wp_nav_menu( array('theme_location' => 'main-menu') ) ?>
                </nav>
            </div>
        </div>
    </div>
</header>   
<div id="content">