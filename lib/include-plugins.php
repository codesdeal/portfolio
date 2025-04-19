<?php

require_once get_template_directory(). '/lib/class-tgm-plugin-activation.php';

add_action('tgmpa_register', 'devabu_register_required_plugins');

function devabu_register_required_plugins() {
    $plugins = array(
        array(
            'name'      => 'devabu metaboxes',
            'slug'      => 'devabu-metaboxes',
            'source' => get_template_directory_uri() . '/lib/plugins/myfirstwptheme-metaboxes.zip',
            'required'  => true,
            'version'   => '1.0.0',
            'force_activation' => false,
            'force_deactivation' => false,
        )
    );

    $config = array(
        'id'           => 'myfirstwptheme',
        'menu'         => 'tgmpa-install-plugins',
        'has_notices'  => true,
        'dismissable'  => true,
        'is_automatic' => true,
    );
    

    tgmpa($plugins, $config);
}