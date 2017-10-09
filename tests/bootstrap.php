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

define('PLUGIN_FILE', getenv('PLUGIN_FILE'));

define('PLUGIN_FOLDER', basename( dirname( __DIR__ )));

define('PLUGIN_PATH', PLUGIN_FOLDER.'/'.PLUGIN_FILE);

$GLOBALS['wp_tests_options'] = [

  'active_plugins' => [PLUGIN_PATH],
];

require_once __DIR__ . '/wp-core-tests/includes/functions.php';

function _manually_load_environment() {

    switch_theme('twentythirteen');

    //require dirname( __DIR__ ) . '/'. PLUGIN_FILE;
}

tests_add_filter('muplugins_loaded', '_manually_load_environment');

require __DIR__ . '/wp-core-tests/includes/bootstrap.php';
