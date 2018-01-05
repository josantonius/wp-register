<?php
/**
 * Register, minify and unify CSS and JavaScript resources.
 *
 * @author    Josantonius <hello@josantonius.com>
 * @package   Josantonius\WP_Register
 * @copyright 2017 - 2018 (c) Josantonius - WP_Register
 * @license   https://opensource.org/licenses/MIT - The MIT License (MIT)
 * @link      https://github.com/Josantonius/WP_Register
 * @since     1.0.4
 */

namespace Josantonius\WP_Register\Test\Register;

use Josantonius\WP_Register\WP_Register;

/**
 * Tests class for WP_Register library.
 */
final class Frontend_Styles_Test extends \WP_UnitTestCase {

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
	 * Add admin style.
	 */
	public function test_add_admin_style() {

		$wp_register = $this->wp_register;

		set_current_screen( 'admin.php' );

		$this->assertTrue(
			$wp_register::add(
				'style', [
					'name'  => 'EditorStyleAdmin',
					'url'   => $this->theme_url . 'css/editor-style.css',
					'place' => 'admin',
				]
			)
		);
	}

	/**
	 * Add admin style without specifying a name. [FALSE|ERROR]
	 */
	public function test_add_admin_style_without_name() {

		$wp_register = $this->wp_register;

		$this->assertFalse(
			$wp_register::add(
				'style', [
					'url'   => $this->theme_url . 'css/unknown.css',
					'place' => 'admin',
				]
			)
		);
	}

	/**
	 * Add admin style without specifying a url. [FALSE|ERROR]
	 */
	public function test_add_admin_style_without_url() {

		$wp_register = $this->wp_register;

		$this->assertFalse(
			$wp_register::add(
				'style', [
					'name'  => 'unknown',
					'place' => 'front',
				]
			)
		);
	}

	/**
	 * Add style for front-end from admin. [FALSE|ERROR]
	 */
	public function test_add_frontend_style_from_admin() {

		$wp_register = $this->wp_register;

		$this->assertFalse(
			$wp_register::add(
				'style', [
					'name'  => 'unknown',
					'url'   => $this->theme_url . 'css/unknown.css',
					'place' => 'front',
				]
			)
		);
	}

	/**
	 * Add admin style by adding all options.
	 */
	public function test_add_admin_style_adding_all_params() {

		$wp_register = $this->wp_register;

		$this->assertTrue(
			$wp_register::add(
				'style', [
					'name'    => 'DefaultStyleAdmin',
					'url'     => $this->theme_url . 'css/style.css',
					'place'   => 'admin',
					'deps'    => [],
					'version' => '1.1.3',
					'media'   => 'all',
				]
			)
		);
	}

	/**
	 * Check if the admin styles have been added correctly.
	 */
	public function test_if_admin_styles_added_correctly() {

		$wp_register = $this->wp_register;

		$this->assertTrue(
			$wp_register::is_added( 'style', 'EditorStyleAdmin' )
		);

		$this->assertFalse(
			$wp_register::is_added( 'style', 'unknown' )
		);

		$this->assertTrue(
			$wp_register::is_added( 'style', 'DefaultStyleAdmin' )
		);
	}

	/**
	 * If admin style is registered.
	 */
	public function test_if_admin_style_is_registered() {

		do_action( 'admin_enqueue_scripts' );

		$this->assertTrue(
			wp_style_is( 'DefaultStyleAdmin', 'registered' )
		);

		$this->assertTrue(
			wp_style_is( 'EditorStyleAdmin', 'registered' )
		);
	}

	/**
	 * If admin style is enqueued.
	 */
	public function test_if_admin_style_is_enqueued() {

		$this->assertTrue(
			wp_style_is( 'DefaultStyleAdmin', 'enqueued' )
		);

		$this->assertTrue(
			wp_style_is( 'EditorStyleAdmin', 'enqueued' )
		);
	}

	/**
	 * If admin style is queue.
	 */
	public function test_if_admin_style_is_queue() {

		$this->assertTrue(
			wp_style_is( 'DefaultStyleAdmin', 'queue' )
		);

		$this->assertTrue(
			wp_style_is( 'EditorStyleAdmin', 'queue' )
		);
	}

	/**
	 * If admin style has been printed.
	 */
	public function test_if_admin_style_is_done() {

		do_action( 'wp_print_footer_scripts' );

		$this->assertTrue(
			wp_style_is( 'DefaultStyleAdmin', 'done' )
		);

		$this->assertTrue(
			wp_style_is( 'EditorStyleAdmin', 'done' )
		);
	}

	/**
	 * Delete added front-end styles.
	 */
	public function test_remove_added_admin_styles() {

		$wp_register = $this->wp_register;

		$this->assertTrue(
			$wp_register::remove( 'style', 'DefaultStyleAdmin' )
		);

		wp_deregister_style( 'DefaultStyleAdmin' );

		wp_dequeue_style( 'DefaultStyleAdmin' );

		$this->assertTrue(
			$wp_register::remove( 'style', 'EditorStyleAdmin' )
		);

		wp_deregister_style( 'EditorStyleAdmin' );

		wp_dequeue_style( 'EditorStyleAdmin' );
	}

	/**
	 * Validation after deletion.
	 */
	public function test_validation_after_deletion() {

		$wp_register = $this->wp_register;

		$this->assertFalse(
			wp_style_is( 'DefaultStyleAdmin', 'queue' )
		);

		$this->assertFalse(
			wp_style_is( 'EditorStyleAdmin', 'queue' )
		);

		$this->assertFalse(
			wp_style_is( 'DefaultStyleAdmin', 'enqueued' )
		);

		$this->assertFalse(
			wp_style_is( 'EditorStyleAdmin', 'enqueued' )
		);

		$this->assertFalse(
			wp_style_is( 'DefaultStyleAdmin', 'registered' )
		);

		$this->assertFalse(
			wp_style_is( 'EditorStyleAdmin', 'registered' )
		);

		$this->assertFalse(
			$wp_register::is_added( 'style', 'DefaultStyleAdmin' )
		);

		$this->assertFalse(
			$wp_register::is_added( 'style', 'EditorStyleAdmin' )
		);
	}
}
