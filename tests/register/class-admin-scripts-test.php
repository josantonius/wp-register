<?php
/**
 * Register, minify and unify CSS and JavaScript resources.
 *
 * @author     Josantonius - hello@josantonius.com
 * @package    Josantonius\WP_Register
 * @copyright  Copyright (c) 2017
 * @license    https://opensource.org/licenses/MIT - The MIT License (MIT)
 * @link       https://github.com/Josantonius/WP_Register
 * @since      1.0.4
 */

namespace Josantonius\WP_Register\Test\Register;

use Josantonius\WP_Register\WP_Register;

/**
 * Tests class for WP_Register library.
 */
final class Admin_Scripts_Test extends \WP_UnitTestCase {

	/**
	 * WP_Register instance.
	 *
	 * @since 1.0.5
	 *
	 * @var object
	 */
	protected $wp_register;

	/**
	 * Theme url.
	 *
	 * @var string
	 */
	protected $theme_url;

	/**
	 * Set up.
	 */
	public function setUp() {

		$this->wp_register = new WP_Register();

		$this->theme_url = get_theme_root_uri() . '/tests/';

		set_current_screen( 'admin.php' );
	}

	/**
	 * Check if it is an instance of WP_Register.
	 *
	 * @since 1.0.5
	 */
	public function test_is_instance_of() {

		$this->assertInstanceOf(
			'Josantonius\WP_Register\WP_Register',
			$this->wp_register
		);
	}

	/**
	 * Add admin script.
	 */
	public function test_add_admin_script() {

		$wp_register = $this->wp_register;

		$this->assertTrue(
			$wp_register::add(
				'script', [
					'name'  => 'HTML_scriptAdmin',
					'url'   => $this->theme_url . 'js/html5.js',
					'place' => 'admin',
				]
			)
		);
	}

	/**
	 * Add admin script without specifying a name. [FALSE|ERROR]
	 */
	public function test_add_admin_script_without_name() {

		$wp_register = $this->wp_register;

		$this->assertFalse(
			$wp_register::add(
				'script', [
					'url'   => $this->theme_url . 'js/unknown.js',
					'place' => 'admin',
				]
			)
		);
	}

	/**
	 * Add admin script without specifying a url. [FALSE|ERROR]
	 */
	public function test_add_admin_script_without_url() {

		$wp_register = $this->wp_register;

		$this->assertFalse(
			$wp_register::add(
				'script', [
					'name'  => 'unknown',
					'place' => 'front',
				]
			)
		);
	}

	/**
	 * Add script for front-end from admin. [FALSE|ERROR]
	 */
	public function test_add_frontend_script_from_admin() {

		$wp_register = $this->wp_register;

		$this->assertFalse(
			$wp_register::add(
				'script', [
					'name'  => 'unknown',
					'url'   => $this->theme_url . 'js/unknown.js',
					'place' => 'front',
				]
			)
		);
	}

	/**
	 * Add admin script by adding all options.
	 */
	public function test_add_admin_script_adding_all_params() {

		$wp_register = $this->wp_register;

		$this->assertTrue(
			$wp_register::add(
				'script', [
					'name'    => 'NavigationScriptAdmin',
					'url'     => $this->theme_url . 'js/navigation.js',
					'place'   => 'admin',
					'deps'    => [ 'jquery' ],
					'version' => '1.1.8',
					'footer'  => false,
					'params'  => [ 'date' => date( 'now' ) ],
				]
			)
		);
	}

	/**
	 * Check if the admin scripts have been added correctly.
	 */
	public function test_if_admin_scripts_added_correctly() {

		$wp_register = $this->wp_register;

		$this->assertTrue(
			$wp_register::is_added( 'script', 'HTML_scriptAdmin' )
		);

		$this->assertFalse(
			$wp_register::is_added( 'script', 'unknown' )
		);

		$this->assertTrue(
			$wp_register::is_added( 'script', 'NavigationScriptAdmin' )
		);
	}

	/**
	 * If admin script is registered.
	 */
	public function test_if_admin_script_is_registered() {

		do_action( 'admin_enqueue_scripts' );

		$this->assertTrue(
			wp_script_is( 'NavigationScriptAdmin', 'registered' )
		);

		$this->assertTrue(
			wp_script_is( 'HTML_scriptAdmin', 'registered' )
		);
	}

	/**
	 * If admin script is enqueued.
	 */
	public function test_if_admin_script_is_enqueued() {

		$this->assertTrue(
			wp_script_is( 'NavigationScriptAdmin', 'enqueued' )
		);

		$this->assertTrue(
			wp_script_is( 'HTML_scriptAdmin', 'enqueued' )
		);
	}

	/**
	 * If admin script is queue.
	 */
	public function test_if_admin_script_is_queue() {

		$this->assertTrue(
			wp_script_is( 'NavigationScriptAdmin', 'queue' )
		);

		$this->assertTrue(
			wp_script_is( 'HTML_scriptAdmin', 'queue' )
		);
	}

	/**
	 * If admin script has been printed.
	 */
	public function test_if_admin_script_is_done() {

		do_action( 'wp_print_footer_scripts' );

		$this->assertTrue(
			wp_script_is( 'NavigationScriptAdmin', 'done' )
		);

		$this->assertTrue(
			wp_script_is( 'HTML_scriptAdmin', 'done' )
		);
	}

	/**
	 * Delete added front-end styles.
	 */
	public function test_remove_added_admin_scripts() {

		$wp_register = $this->wp_register;

		$this->assertTrue(
			$wp_register::remove( 'script', 'NavigationScriptAdmin' )
		);

		wp_deregister_script( 'NavigationScriptAdmin' );

		wp_dequeue_script( 'NavigationScriptAdmin' );

		$this->assertTrue(
			$wp_register::remove( 'script', 'HTML_scriptAdmin' )
		);

		wp_deregister_script( 'HTML_scriptAdmin' );

		wp_dequeue_script( 'HTML_scriptAdmin' );
	}

	/**
	 * Validation after deletion.
	 */
	public function test_validation_after_deletion() {

		$wp_register = $this->wp_register;

		$this->assertFalse(
			wp_script_is( 'NavigationScriptAdmin', 'queue' )
		);

		$this->assertFalse(
			wp_script_is( 'HTML_scriptAdmin', 'queue' )
		);

		$this->assertFalse(
			wp_script_is( 'NavigationScriptAdmin', 'enqueued' )
		);

		$this->assertFalse(
			wp_script_is( 'HTML_scriptAdmin', 'enqueued' )
		);

		$this->assertFalse(
			wp_script_is( 'NavigationScriptAdmin', 'registered' )
		);

		$this->assertFalse(
			wp_script_is( 'HTML_scriptAdmin', 'registered' )
		);

		$this->assertFalse(
			$wp_register::is_added( 'script', 'NavigationScriptAdmin' )
		);

		$this->assertFalse(
			$wp_register::is_added( 'script', 'HTML_scriptAdmin' )
		);
	}
}
