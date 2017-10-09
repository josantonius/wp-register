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
final class RegisterScriptsTest extends \WP_UnitTestCase { 

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
     * Add front-end script.
     *
     * @since 1.0.4
     *
     * @return void
     */
    public function testAddFrontEndScript() {
        
        $this->assertTrue(

            WP_Register::add('script', [

                'name'  => 'HTML_script',
                'url'   => $this->themeUrl . 'js/html5.js'
            ])
        );
    }

    /**
     * Add front-end script without specifying a name. [FALSE|ERROR]
     *
     * @since 1.0.4
     *
     * @return void
     */
    public function testAddFrontEndScriptWithoutName() {

        $this->assertFalse(

            WP_Register::add('script', [

                'url'   => $this->themeUrl . 'js/unknown.js',
                'place' => 'front'
            ])
        );
    }

    /**
     * Add front-end script without specifying a url. [FALSE|ERROR]
     *
     * @since 1.0.4
     *
     * @return void
     */
    public function testAddFrontEndScriptWithoutUrl() {
        
        $this->assertFalse(

            WP_Register::add('script', [

                'name'  => 'unknown',
                'place' => 'front'
            ])
        );
    }

    /**
     * Add script for admin from front-end. [FALSE|ERROR]
     *
     * @since 1.0.4
     *
     * @return void
     */
    public function testAddAdminScriptFromFrontEnd() {
        
        $this->assertFalse(

            WP_Register::add('script', [

                'name'  => 'unknown',
                'url'   => $this->themeUrl . 'js/unknown.js',
                'place' => 'admin'
            ])
        );
    }

    /**
     * Add front-end script by adding all options.
     *
     * @since 1.0.4
     *
     * @return void
     */
    public function testAddFrontEndScriptAddingAllParams() {

        $this->assertTrue(

            WP_Register::add('script', [

                'name'    => 'NavigationScript',
                'url'     => $this->themeUrl . 'js/navigation.js',
                'place'   => 'front',
                'deps'    => ['jquery'],
                'version' => '1.1.3',
                'footer'  => true,
                'params'  => ['date' => date('now')],
            ])
        );
    }

    /**
     * Check if the front-end scripts have been added correctly.
     *
     * @since 1.0.4
     *
     * @return void
     */
    public function testIfFrontEndScriptsAddedCorrectly() {

        $this->assertTrue(

            WP_Register::isAdded('script', 'HTML_script')
        );

        $this->assertFalse(

            WP_Register::isAdded('script', 'unknown')
        );

        $this->assertTrue(

            WP_Register::isAdded('script', 'NavigationScript')
        );
    }
    /**
     * If front-end script is registered.
     *
     * @since 1.0.4
     *
     * @return void
     */
    public function testIfFrontEndScriptIsRegistered() {

        do_action('wp_enqueue_scripts');

        $this->assertTrue(

            wp_script_is('NavigationScript', 'registered')
        );

        $this->assertTrue(

            wp_script_is('HTML_script', 'registered')
        );
    }

    /**
     * If front-end script is enqueued.
     *
     * @since 1.0.4
     *
     * @return void
     */
    public function testIfFrontEndScriptIsEnqueued() {

        $this->assertTrue(

            wp_script_is('NavigationScript', 'enqueued')
        );

        $this->assertTrue(

            wp_script_is('HTML_script', 'enqueued')
        );
    }

    /**
     * If front-end script is queue.
     *
     * @since 1.0.4
     *
     * @return void
     */
    public function testIfFrontEndScriptIsQueue() {

        $this->assertTrue(

            wp_script_is('NavigationScript', 'queue')
        );

        $this->assertTrue(

            wp_script_is('HTML_script', 'queue')
        );
    }

    /**
     * If front-end script has been printed.
     *
     * @since 1.0.4
     *
     * @return void
     */
    public function testIfFrontEndScriptIsDone() {

        do_action('wp_print_footer_scripts');

        $this->assertTrue(

            wp_script_is('NavigationScript', 'done')
        );

        $this->assertTrue(

            wp_script_is('HTML_script', 'done')
        );
    }

    /**
     * Delete added front-end styles.
     *
     * @since 1.0.4
     *
     * @return void
     */
    public function testRemoveAddedFrontEndScripts() {

        $this->assertTrue(

            WP_Register::remove('script', 'NavigationScript')
        );

        wp_deregister_script('NavigationScript');
        
        wp_dequeue_script('NavigationScript');

        $this->assertTrue(

            WP_Register::remove('script', 'HTML_script')
        );

        wp_deregister_script('HTML_script');

        wp_dequeue_script('HTML_script');
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

            wp_script_is('NavigationScript', 'queue')
        );

        $this->assertFalse(

            wp_script_is('HTML_script', 'queue')
        );

        $this->assertFalse(

            wp_script_is('NavigationScript', 'enqueued')
        );

        $this->assertFalse(

            wp_script_is('HTML_script', 'enqueued')
        );

        $this->assertFalse(

            wp_script_is('NavigationScript', 'registered')
        );

        $this->assertFalse(

            wp_script_is('HTML_script', 'registered')
        );

        $this->assertFalse(

            WP_Register::isAdded('script', 'NavigationScript')
        );

        $this->assertFalse(

            WP_Register::isAdded('script', 'HTML_script')
        );
    }
}
