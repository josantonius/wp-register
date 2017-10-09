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

$testDir = getenv('WP_TESTS_DIR');

if (!$testDir) {

	$testDir = '/tmp/wordpress-tests-lib';
}

require_once $testDir . '/includes/functions.php';

function _manually_load_environment() {

    switch_theme('twentythirteen');

    //require dirname( dirname( __FILE__ ) ) . '/sample-plugin.php';
}

tests_add_filter('muplugins_loaded', '_manually_load_environment');

require_once $testDir . '/includes/bootstrap.php';
