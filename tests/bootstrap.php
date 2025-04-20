<?php
require_once dirname(__DIR__) . '/vendor/autoload.php';
require_once dirname(__DIR__) . '/vendor/10up/wp_mock/WP_Mock.php';

WP_Mock::setUsePatchwork(true);
WP_Mock::bootstrap();

$_tests_dir = getenv('WP_TESTS_DIR');
if (!$_tests_dir) {
    $_tests_dir = '/tmp/wordpress-tests-lib';
}

function _manually_load_theme() {
    require dirname(__DIR__) . '/functions.php';
}