<?php 
/**
 * Register, minify and unify CSS and JavaScript resources.
 * 
 * @author     Josantonius - hello@josantonius.com
 * @copyright  Copyright (c) 2017
 * @license    https://opensource.org/licenses/MIT - The MIT License (MIT)
 * @link       https://github.com/Josantonius/WP_Register
 * @since      1.0.4
 */

$loader = require __DIR__ . '/../src/bootstrap.php';

$loader->add('Josantonius\WP_Register\Test', __DIR__);

require_once __DIR__ . '/wp-core-tests/includes/functions.php';

function _manually_load_environment() {

    switch_theme('twentythirteen');

    $plugins_to_active = array(
        __DIR__ . '/wp-core-tests/wordpress/wp-content/plugins/hello.php'
    );

    update_option('active_plugins', $plugins_to_active);
}

tests_add_filter('muplugins_loaded', '_manually_load_environment');

require_once __DIR__ . '/wp-core-tests/includes/bootstrap.php';
