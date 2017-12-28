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
final class Frontend_Scripts_Test extends \WP_UnitTestCase {

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
	 * Add front-end script.
	 */
	public function test_add_frontend_script() {

		$wp_register = $this->wp_register;

		$this->assertTrue(
			$wp_register::add(
				'script', [
					'name' => 'HTML_script',
					'url'  => $this->theme_url . 'js/html5.js',
				]
			)
		);
	}

	/**
	 * Add front-end script without specifying a name. [FALSE|ERROR]
	 */
	public function test_add_frontend_script_without_name() {

		$wp_register = $this->wp_register;

		$this->assertFalse(
			$wp_register::add(
				'script', [
					'url'   => $this->theme_url . 'js/unknown.js',
					'place' => 'front',
				]
			)
		);
	}

	/**
	 * Add front-end script without specifying a url. [FALSE|ERROR]
	 */
	public function test_add_frontend_script_without_url() {

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
	 * Add script for admin from front-end. [FALSE|ERROR]
	 */
	public function test_add_admin_script_from_frontend() {

		$wp_register = $this->wp_register;

		$this->assertFalse(
			$wp_register::add(
				'script', [
					'name'  => 'unknown',
					'url'   => $this->theme_url . 'js/unknown.js',
					'place' => 'admin',
				]
			)
		);
	}

	/**
	 * Add front-end script by adding all options.
	 */
	public function test_add_frontend_script_adding_all_params() {

		$wp_register = $this->wp_register;

		$this->assertTrue(
			$wp_register::add(
				'script', [
					'name'    => 'NavigationScript',
					'url'     => $this->theme_url . 'js/navigation.js',
					'place'   => 'front',
					'deps'    => [ 'jquery' ],
					'version' => '1.1.3',
					'footer'  => true,
					'params'  => [ 'date' => date( 'now' ) ],
				]
			)
		);
	}

	/**
	 * Check if the front-end scripts have been added correctly.
	 */
	public function test_if_front_end_script_is_added_correctly() {

		$wp_register = $this->wp_register;

		$this->assertTrue(
			$wp_register::is_added( 'script', 'HTML_script' )
		);

		$this->assertFalse(
			$wp_register::is_added( 'script', 'unknown' )
		);

		$this->assertTrue(
			$wp_register::is_added( 'script', 'NavigationScript' )
		);
	}
	/**
	 * If front-end script is registered.
	 */
	public function test_if_front_end_script_is_registered() {

		do_action( 'wp_enqueue_scripts' );

		$this->assertTrue(
			wp_script_is( 'NavigationScript', 'registered' )
		);

		$this->assertTrue(
			wp_script_is( 'HTML_script', 'registered' )
		);
	}

	/**
	 * If front-end script is enqueued.
	 */
	public function test_if_front_end_script_is_enqueued() {

		$this->assertTrue(
			wp_script_is( 'NavigationScript', 'enqueued' )
		);

		$this->assertTrue(
			wp_script_is( 'HTML_script', 'enqueued' )
		);
	}

	/**
	 * If front-end script is queue.
	 */
	public function test_if_front_end_script_is_queue() {

		$this->assertTrue(
			wp_script_is( 'NavigationScript', 'queue' )
		);

		$this->assertTrue(
			wp_script_is( 'HTML_script', 'queue' )
		);
	}

	/**
	 * If front-end script has been printed.
	 */
	public function test_if_front_end_script_is_Done() {

		do_action( 'wp_print_footer_scripts' );

		$this->assertTrue(
			wp_script_is( 'NavigationScript', 'done' )
		);

		$this->assertTrue(
			wp_script_is( 'HTML_script', 'done' )
		);
	}

	/**
	 * Delete added front-end styles.
	 */
	public function test_remove_added_frontend_scripts() {

		$wp_register = $this->wp_register;

		$this->assertTrue(
			$wp_register::remove( 'script', 'NavigationScript' )
		);

		wp_deregister_script( 'NavigationScript' );

		wp_dequeue_script( 'NavigationScript' );

		$this->assertTrue(
			$wp_register::remove( 'script', 'HTML_script' )
		);

		wp_deregister_script( 'HTML_script' );

		wp_dequeue_script( 'HTML_script' );
	}

	/**
	 * Validation after deletion.
	 */
	public function test_validation_after_deletion() {

		$wp_register = $this->wp_register;

		$this->assertFalse(
			wp_script_is( 'NavigationScript', 'queue' )
		);

		$this->assertFalse(
			wp_script_is( 'HTML_script', 'queue' )
		);

		$this->assertFalse(
			wp_script_is( 'NavigationScript', 'enqueued' )
		);

		$this->assertFalse(
			wp_script_is( 'HTML_script', 'enqueued' )
		);

		$this->assertFalse(
			wp_script_is( 'NavigationScript', 'registered' )
		);

		$this->assertFalse(
			wp_script_is( 'HTML_script', 'registered' )
		);

		$this->assertFalse(
			$wp_register::is_added( 'script', 'NavigationScript' )
		);

		$this->assertFalse(
			$wp_register::is_added( 'script', 'HTML_script' )
		);
	}
}
