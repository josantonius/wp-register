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
final class RegisterAdminStylesTest extends \WP_UnitTestCase { 

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
     * Add admin style.
     *
     * @since 1.0.4
     *
     * @return void
     */
    public function testAddAdminStyle() {
        
        set_current_screen('admin.php');

        $this->assertTrue(

            WP_Register::add('style', [

                'name'  => 'EditorStyleAdmin',
                'url'   => $this->themeUrl . 'editor-style.css',
                'place' => 'admin'
            ])
        );
    }

    /**
     * Add admin style without specifying a name. [FALSE|ERROR]
     *
     * @since 1.0.4
     *
     * @return void
     */
    public function testAddAdminStyleWithoutName() {

        $this->assertFalse(

            WP_Register::add('style', [

                'url'   => $this->themeUrl . 'unknown.css',
                'place' => 'admin'
            ])
        );
    }

    /**
     * Add admin style without specifying a url. [FALSE|ERROR]
     *
     * @since 1.0.4
     *
     * @return void
     */
    public function testAddAdminStyleWithoutUrl() {
    

        $this->assertFalse(

            WP_Register::add('style', [

                'name'  => 'unknown',
                'place' => 'front'
            ])
        );
    }
    
    /**
     * Add style for front-end from admin. [FALSE|ERROR]
     *
     * @since 1.0.4
     *
     * @return void
     */
    public function testAddFrontEndStyleFromAdmin() {


        $this->assertFalse(

            WP_Register::add('style', [

                'name'  => 'unknown',
                'url'   => $this->themeUrl . 'unknown.css',
                'place' => 'front'
            ])
        );
    }

    /**
     * Add admin style by adding all options.
     *
     * @since 1.0.4
     *
     * @return void
     */
    public function testAddAdminStyleAddingAllParams() {

        $this->assertTrue(

            WP_Register::add('style', [

                'name'    => 'DefaultStyleAdmin',
                'url'     => $this->themeUrl . 'style.css',
                'place'   => 'admin',
                'deps'    => [],
                'version' => '1.1.3',
                'media'   => 'all'
            ])
        );
    }

    /**
     * Check if the admin styles have been added correctly.
     *
     * @since 1.0.4
     *
     * @return void
     */
    public function testIfAdminStylesAddedCorrectly() {

        $this->assertTrue(

            WP_Register::isAdded('style', 'EditorStyleAdmin')
        );

        $this->assertFalse(

            WP_Register::isAdded('style', 'unknown')
        );

        $this->assertTrue(

            WP_Register::isAdded('style', 'DefaultStyleAdmin')
        );
    }

    /**
     * If admin style is registered.
     *
     * @since 1.0.4
     *
     * @return void
     */
    public function testIfAdminStyleIsRegistered() {

        do_action('admin_enqueue_scripts');

        $this->assertTrue(

            wp_style_is('DefaultStyleAdmin', 'registered')
        );

        $this->assertTrue(

            wp_style_is('EditorStyleAdmin', 'registered')
        );
    }

    /**
     * If admin style is enqueued.
     *
     * @since 1.0.4
     *
     * @return void
     */
    public function testIfAdminStyleIsEnqueued() {

        $this->assertTrue(

            wp_style_is('DefaultStyleAdmin', 'enqueued')
        );

        $this->assertTrue(

            wp_style_is('EditorStyleAdmin', 'enqueued')
        );
    }

    /**
     * If admin style is queue.
     *
     * @since 1.0.4
     *
     * @return void
     */
    public function testIfAdminStyleIsQueue() {

        $this->assertTrue(

            wp_style_is('DefaultStyleAdmin', 'queue')
        );

        $this->assertTrue(

            wp_style_is('EditorStyleAdmin', 'queue')
        );
    }

    /**
     * If admin style has been printed.
     *
     * @since 1.0.4
     *
     * @return void
     */
    public function testIfAdminStyleIsDone() {

        do_action('wp_print_footer_scripts');

        $this->assertTrue(

            wp_style_is('DefaultStyleAdmin', 'done')
        );

        $this->assertTrue(

            wp_style_is('EditorStyleAdmin', 'done')
        );
    }

    /**
     * Delete added front-end styles.
     *
     * @since 1.0.4
     *
     * @return void
     */
    public function testRemoveAddedAdminStyles() {

        $this->assertTrue(

            WP_Register::remove('style', 'DefaultStyleAdmin')
        );

        wp_deregister_style('DefaultStyleAdmin');
        
        wp_dequeue_style('DefaultStyleAdmin');

        $this->assertTrue(

            WP_Register::remove('style', 'EditorStyleAdmin')
        );

        wp_deregister_style('EditorStyleAdmin');

        wp_dequeue_style('EditorStyleAdmin');
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

            wp_style_is('DefaultStyleAdmin', 'queue')
        );

        $this->assertFalse(

            wp_style_is('EditorStyleAdmin', 'queue')
        );

        $this->assertFalse(

            wp_style_is('DefaultStyleAdmin', 'enqueued')
        );

        $this->assertFalse(

            wp_style_is('EditorStyleAdmin', 'enqueued')
        );

        $this->assertFalse(

            wp_style_is('DefaultStyleAdmin', 'registered')
        );

        $this->assertFalse(

            wp_style_is('EditorStyleAdmin', 'registered')
        );

        $this->assertFalse(

            WP_Register::isAdded('style', 'DefaultStyleAdmin')
        );

        $this->assertFalse(

            WP_Register::isAdded('style', 'EditorStyleAdmin')
        );
    }
}
