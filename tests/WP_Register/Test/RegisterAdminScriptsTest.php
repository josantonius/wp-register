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
final class RegisterAdminScriptsTest extends \WP_UnitTestCase { 

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

        $this->themeUrl = get_theme_root_uri() . '/tests/';

        set_current_screen('admin.php');
    }

    /**
     * Add admin script.
     *
     * @since 1.0.4
     *
     * @return void
     */
    public function testAddAdminScript() {
        
        $this->assertTrue(

            WP_Register::add('script', [

                'name'  => 'HTML_scriptAdmin',
                'url'   => $this->themeUrl . 'js/html5.js',
                'place' => 'admin'
            ])
        );
    }

    /**
     * Add admin script without specifying a name. [FALSE|ERROR]
     *
     * @since 1.0.4
     *
     * @return void
     */
    public function testAddAdminScriptWithoutName() {

        $this->assertFalse(

            WP_Register::add('script', [

                'url'   => $this->themeUrl . 'js/unknown.js',
                'place' => 'admin'
            ])
        );
    }

    /**
     * Add admin script without specifying a url. [FALSE|ERROR]
     *
     * @since 1.0.4
     *
     * @return void
     */
    public function testAddAdminScriptWithoutUrl() {
    

        $this->assertFalse(

            WP_Register::add('script', [

                'name'  => 'unknown',
                'place' => 'front'
            ])
        );
    }
    
    /**
     * Add script for front-end from admin. [FALSE|ERROR]
     *
     * @since 1.0.4
     *
     * @return void
     */
    public function testAddFrontEndScriptFromAdmin() {


        $this->assertFalse(

            WP_Register::add('script', [

                'name'  => 'unknown',
                'url'   => $this->themeUrl . 'js/unknown.js',
                'place' => 'front'
            ])
        );
    }

    /**
     * Add admin script by adding all options.
     *
     * @since 1.0.4
     *
     * @return void
     */
    public function testAddAdminScriptAddingAllParams() {

        $this->assertTrue(

            WP_Register::add('script', [

                'name'    => 'NavigationScriptAdmin',
                'url'     => $this->themeUrl . 'js/navigation.js',
                'place'   => 'admin',
                'deps'    => ['jquery'],
                'version' => '1.1.8',
                'footer'  => false,
                'params'  => ['date' => date('now')],
            ])
        );
    }

    /**
     * Check if the admin scripts have been added correctly.
     *
     * @since 1.0.4
     *
     * @return void
     */
    public function testIfAdminScriptsAddedCorrectly() {

        $this->assertTrue(

            WP_Register::isAdded('script', 'HTML_scriptAdmin')
        );

        $this->assertFalse(

            WP_Register::isAdded('script', 'unknown')
        );

        $this->assertTrue(

            WP_Register::isAdded('script', 'NavigationScriptAdmin')
        );
    }

    /**
     * If admin script is registered.
     *
     * @since 1.0.4
     *
     * @return void
     */
    public function testIfAdminScriptIsRegistered() {

        do_action('admin_enqueue_scripts');

        $this->assertTrue(

            wp_script_is('NavigationScriptAdmin', 'registered')
        );

        $this->assertTrue(

            wp_script_is('HTML_scriptAdmin', 'registered')
        );
    }

    /**
     * If admin script is enqueued.
     *
     * @since 1.0.4
     *
     * @return void
     */
    public function testIfAdminScriptIsEnqueued() {

        $this->assertTrue(

            wp_script_is('NavigationScriptAdmin', 'enqueued')
        );

        $this->assertTrue(

            wp_script_is('HTML_scriptAdmin', 'enqueued')
        );
    }

    /**
     * If admin script is queue.
     *
     * @since 1.0.4
     *
     * @return void
     */
    public function testIfAdminScriptIsQueue() {

        $this->assertTrue(

            wp_script_is('NavigationScriptAdmin', 'queue')
        );

        $this->assertTrue(

            wp_script_is('HTML_scriptAdmin', 'queue')
        );
    }

    /**
     * If admin script has been printed.
     *
     * @since 1.0.4
     *
     * @return void
     */
    public function testIfAdminScriptIsDone() {

        do_action('wp_print_footer_scripts');

        $this->assertTrue(

            wp_script_is('NavigationScriptAdmin', 'done')
        );

        $this->assertTrue(

            wp_script_is('HTML_scriptAdmin', 'done')
        );
    }

    /**
     * Delete added front-end styles.
     *
     * @since 1.0.4
     *
     * @return void
     */
    public function testRemoveAddedAdminScripts() {

        $this->assertTrue(

            WP_Register::remove('script', 'NavigationScriptAdmin')
        );

        wp_deregister_script('NavigationScriptAdmin');
        
        wp_dequeue_script('NavigationScriptAdmin');

        $this->assertTrue(

            WP_Register::remove('script', 'HTML_scriptAdmin')
        );

        wp_deregister_script('HTML_scriptAdmin');

        wp_dequeue_script('HTML_scriptAdmin');
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

            wp_script_is('NavigationScriptAdmin', 'queue')
        );

        $this->assertFalse(

            wp_script_is('HTML_scriptAdmin', 'queue')
        );

        $this->assertFalse(

            wp_script_is('NavigationScriptAdmin', 'enqueued')
        );

        $this->assertFalse(

            wp_script_is('HTML_scriptAdmin', 'enqueued')
        );

        $this->assertFalse(

            wp_script_is('NavigationScriptAdmin', 'registered')
        );

        $this->assertFalse(

            wp_script_is('HTML_scriptAdmin', 'registered')
        );

        $this->assertFalse(

            WP_Register::isAdded('script', 'NavigationScriptAdmin')
        );

        $this->assertFalse(

            WP_Register::isAdded('script', 'HTML_scriptAdmin')
        );
    }
}
