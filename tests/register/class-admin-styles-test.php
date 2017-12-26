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
 *
 * @since 1.0.4
 */
final class Admin_Styles_Test extends \WP_UnitTestCase {

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
	 * Add front-end style.
	 */
	public function test_add_frontend_style() {

		$this->assertTrue(
			$this->wp_register::add(
				'style', [
					'name' => 'EditorStyle',
					'url'  => $this->theme_url . 'css/editor-style.css',
				]
			)
		);
	}

	/**
	 * Add front-end style without specifying a name. [FALSE|ERROR]
	 */
	public function test_add_frontend_style_without_name() {

		$this->assertFalse(
			$this->wp_register::add(
				'style', [
					'url'   => $this->theme_url . 'css/unknown.css',
					'place' => 'front',
				]
			)
		);
	}

	/**
	 * Add front-end style without specifying a url. [FALSE|ERROR]
	 */
	public function test_add_frontend_style_without_url() {

		$this->assertFalse(
			$this->wp_register::add(
				'style', [
					'name'  => 'unknown',
					'place' => 'front',
				]
			)
		);
	}

	/**
	 * Add style for admin from front-end. [FALSE|ERROR]
	 */
	public function test_add_admin_style_from_frontend() {

		$this->assertFalse(
			$this->wp_register::add(
				'style', [
					'name'  => 'unknown',
					'url'   => $this->theme_url . 'css/unknown.css',
					'place' => 'admin',
				]
			)
		);
	}

	/**
	 * Add front-end style by adding all options.
	 */
	public function test_add_frontend_style_adding_all_params() {

		$this->assertTrue(
			$this->wp_register::add(
				'style', [
					'name'    => 'DefaultStyle',
					'url'     => $this->theme_url . 'css/style.css',
					'place'   => 'front',
					'deps'    => [],
					'version' => '1.1.3',
					'media'   => 'all',
				]
			)
		);
	}

	/**
	 * Check if the front-end styles have been added correctly.
	 */
	public function test_if_front_end_styles_added_correctly() {

		$this->assertTrue(
			$this->wp_register::isAdded( 'style', 'EditorStyle' )
		);

		$this->assertFalse(
			$this->wp_register::isAdded( 'style', 'unknown' )
		);

		$this->assertTrue(
			$this->wp_register::isAdded( 'style', 'DefaultStyle' )
		);
	}
	/**
	 * If front-end style is registered.
	 */
	public function test_if_front_end_style_is_registered() {

		do_action( 'wp_enqueue_scripts' );

		$this->assertTrue(
			wp_style_is( 'DefaultStyle', 'registered' )
		);

		$this->assertTrue(
			wp_style_is( 'EditorStyle', 'registered' )
		);
	}

	/**
	 * If front-end style is enqueued.
	 */
	public function test_if_front_end_style_is_enqueued() {

		$this->assertTrue(
			wp_style_is( 'DefaultStyle', 'enqueued' )
		);

		$this->assertTrue(
			wp_style_is( 'EditorStyle', 'enqueued' )
		);
	}

	/**
	 * If front-end style is queue.
	 */
	public function test_if_front_end_style_is_queue() {

		$this->assertTrue(
			wp_style_is( 'DefaultStyle', 'queue' )
		);

		$this->assertTrue(
			wp_style_is( 'EditorStyle', 'queue' )
		);
	}

	/**
	 * If front-end style has been printed.
	 */
	public function test_if_front_end_style_is_done() {

		do_action( 'wp_print_footer_scripts' );

		$this->assertTrue(
			wp_style_is( 'DefaultStyle', 'done' )
		);

		$this->assertTrue(
			wp_style_is( 'EditorStyle', 'done' )
		);
	}

	/**
	 * Delete added front-end styles.
	 */
	public function test_remove_added_frontend_styles() {

		$this->assertTrue(
			$this->wp_register::remove( 'style', 'DefaultStyle' )
		);

		wp_deregister_style( 'DefaultStyle' );

		wp_dequeue_style( 'DefaultStyle' );

		$this->assertTrue(
			$this->wp_register::remove( 'style', 'EditorStyle' )
		);

		wp_deregister_style( 'EditorStyle' );

		wp_dequeue_style( 'EditorStyle' );
	}

	/**
	 * Validation after deletion.
	 */
	public function test_validation_after_deletion() {

		$this->assertFalse(
			wp_style_is( 'DefaultStyle', 'queue' )
		);

		$this->assertFalse(
			wp_style_is( 'EditorStyle', 'queue' )
		);

		$this->assertFalse(
			wp_style_is( 'DefaultStyle', 'enqueued' )
		);

		$this->assertFalse(
			wp_style_is( 'EditorStyle', 'enqueued' )
		);

		$this->assertFalse(
			wp_style_is( 'DefaultStyle', 'registered' )
		);

		$this->assertFalse(
			wp_style_is( 'EditorStyle', 'registered' )
		);

		$this->assertFalse(
			$this->wp_register::isAdded( 'style', 'DefaultStyle' )
		);

		$this->assertFalse(
			$this->wp_register::isAdded( 'style', 'EditorStyle' )
		);
	}
}
