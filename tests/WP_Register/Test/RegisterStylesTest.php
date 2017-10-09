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
namespace Josantonius\WP_Register\Test;

use Josantonius\WP_Register\WP_Register;

/**
 * Tests class for WP_Register library.
 *
 * @since 1.0.4
 */
final class RegisterStylesTest extends \WP_UnitTestCase { 

    /**
     * Theme url.
     *
     * @since 1.0.4
     *
     * @var string
     */
    protected $themeUrl;

    /**
     * Set up.
     *
     * @since 1.0.4
     *
     * @return void
     */
    public function setUp() {

        parent::setUp();

        $this->themeUrl = get_theme_root_uri() . '/twentytwelve/';
    }

    /**
     * Test correct active theme.
     *
     * @since 1.0.4
     *
     * @return void
     */
    public function testCorrectActiveTheme() {

        $this->assertTrue('Twenty Thirteen' == wp_get_theme()->name);
    }

    /**
     * Add front-end style.
     *
     * @since 1.0.4
     *
     * @return void
     */
    public function testAddFrontEndStyle() {
        
        $this->assertTrue(

            WP_Register::add('style', [

                'name'  => 'EditorStyle',
                'url'   => $this->themeUrl . 'editor-style.css'
            ])
        );
    }

    /**
     * Add front-end style without specifying a name. [FALSE|ERROR]
     *
     * @since 1.0.4
     *
     * @return void
     */
    public function testAddFrontEndStyleWithoutName() {

        $this->assertFalse(

            WP_Register::add('style', [

                'url'   => $this->themeUrl . 'unknown.css',
                'place' => 'front'
            ])
        );
    }

    /**
     * Add front-end style without specifying a url. [FALSE|ERROR]
     *
     * @since 1.0.4
     *
     * @return void
     */
    public function testAddFrontEndStyleWithoutUrl() {
        
        $this->assertFalse(

            WP_Register::add('style', [

                'name'  => 'unknown',
                'place' => 'front'
            ])
        );
    }

    /**
     * Add style for admin from front-end. [FALSE|ERROR]
     *
     * @since 1.0.4
     *
     * @return void
     */
    public function testAddAdminStyleFromFrontEnd() {
        
        $this->assertFalse(

            WP_Register::add('style', [

                'name'  => 'unknown',
                'url'   => $this->themeUrl . 'unknown.css',
                'place' => 'admin'
            ])
        );
    }

    /**
     * Add front-end style by adding all options.
     *
     * @since 1.0.4
     *
     * @return void
     */
    public function testAddFrontEndStyleAddingAllParams() {

        $this->assertTrue(

            WP_Register::add('style', [

                'name'    => 'DefaultStyle',
                'url'     => $this->themeUrl . 'style.css',
                'place'   => 'front',
                'deps'    => [],
                'version' => '1.1.3',
                'media'   => 'all'
            ])
        );
    }

    /**
     * Check if the front-end styles have been added correctly.
     *
     * @since 1.0.4
     *
     * @return void
     */
    public function testIfFrontEndStylesAddedCorrectly() {

        $this->assertTrue(

            WP_Register::isAdded('style', 'EditorStyle')
        );

        $this->assertFalse(

            WP_Register::isAdded('style', 'unknown')
        );

        $this->assertTrue(

            WP_Register::isAdded('style', 'DefaultStyle')
        );
    }
    /**
     * If front-end style is registered.
     *
     * @since 1.0.4
     *
     * @return void
     */
    public function testIfFrontEndStyleIsRegistered() {

        do_action('wp_enqueue_scripts');

        $this->assertTrue(

            wp_style_is('DefaultStyle', 'registered')
        );

        $this->assertTrue(

            wp_style_is('EditorStyle', 'registered')
        );
    }

    /**
     * If front-end style is enqueued.
     *
     * @since 1.0.4
     *
     * @return void
     */
    public function testIfFrontEndStyleIsEnqueued() {

        $this->assertTrue(

            wp_style_is('DefaultStyle', 'enqueued')
        );

        $this->assertTrue(

            wp_style_is('EditorStyle', 'enqueued')
        );
    }

    /**
     * If front-end style is queue.
     *
     * @since 1.0.4
     *
     * @return void
     */
    public function testIfFrontEndStyleIsQueue() {

        $this->assertTrue(

            wp_style_is('DefaultStyle', 'queue')
        );

        $this->assertTrue(

            wp_style_is('EditorStyle', 'queue')
        );
    }

    /**
     * If front-end style has been printed.
     *
     * @since 1.0.4
     *
     * @return void
     */
    public function testIfFrontEndStyleIsDone() {

        do_action('wp_print_footer_scripts');

        $this->assertTrue(

            wp_style_is('DefaultStyle', 'done')
        );

        $this->assertTrue(

            wp_style_is('EditorStyle', 'done')
        );
    }

    /**
     * Delete added front-end styles.
     *
     * @since 1.0.4
     *
     * @return void
     */
    public function testRemoveAddedFrontEndStyles() {

        $this->assertTrue(

            WP_Register::remove('style', 'DefaultStyle')
        );

        wp_deregister_style('DefaultStyle');

        wp_dequeue_style('DefaultStyle');

        $this->assertTrue(

            WP_Register::remove('style', 'EditorStyle')
        );

        wp_deregister_style('EditorStyle');

        wp_dequeue_style('EditorStyle');
    }

    /**
     * Validation after deletion.
     *
     * @since 1.0.4
     *
     * @return void
     */
    public function testValidationAfterDeletion() {

        $this->assertFalse(

            wp_style_is('DefaultStyle', 'queue')
        );

        $this->assertFalse(

            wp_style_is('EditorStyle', 'queue')
        );

        $this->assertFalse(

            wp_style_is('DefaultStyle', 'enqueued')
        );

        $this->assertFalse(

            wp_style_is('EditorStyle', 'enqueued')
        );

        $this->assertFalse(

            wp_style_is('DefaultStyle', 'registered')
        );

        $this->assertFalse(

            wp_style_is('EditorStyle', 'registered')
        );

        $this->assertFalse(

            WP_Register::isAdded('style', 'DefaultStyle')
        );

        $this->assertFalse(

            WP_Register::isAdded('style', 'EditorStyle')
        );
    }
}
