<?php
/**
 * Search Inside Wordpress Plugin.
 * 
 * @author     Josantonius - hello@josantonius.com
 * @copyright  Copyright (c) 2017
 * @license    GPL-2.0+
 * @link       https://github.com/Josantonius/WP-SearchInside.git
 * @since      1.0.0
 */

namespace SearchInside\Controller;

use Josantonius\WP_Notice\WP_Notice,
    Josantonius\WP_Register\WP_Register,
    Eliasis\Controller\Controller,
    Eliasis\App\App;


/**
 * Plugin core, configurations and registration of principal methods.
 *
 * @since 1.0.0
 */
class Launcher extends Controller {

    /**
     * Establish routes and load configurations and debug.
     *
     * @since 1.0.0
     */
    public function __construct() {

        add_shortcode('add-search-inside', 'addShortcode');

        WP_Register::add('script', App::assets('js',  'searchinside'));
        WP_Register::add('script', App::assets('js',  'hilitor'));
        WP_Register::add('style',  App::assets('css', 'searchinside'));
    }

    /**
     * Add shortcode.
     * 
     * @since 1.1.2
     *
     * @return string → html div tag
     */
    private static function addShortcode() {

        return '<div id="search-inside-sc"></div>';
    }

    /**
     * Load default settings.
     * 
     * @since 1.0.0
     */
    public static function init() {


    }





    /**
     * Hook plugin activation. | Executed only when activating the plugin.
     * 
     * @since 1.0.0
     *
     * @uses check_admin_referer() → user was referred from another admin page
     * @uses get_option()          → option value based on an option name
     * @uses add_option()          → add a new option to Wordpress options
     * @uses update_option()       → update a named option/value
     * @uses flush_rewrite_rules() → remove rewrite rules and then recreate news
     */
    public function activation() {

        $plugin = isset( $_REQUEST['plugin'] ) ? $_REQUEST['plugin'] : '';

        check_admin_referer("activate-plugin_{$plugin}");

        $actualVersion = self::get_option('version');

        if (!$installed_version = get_option('searchinside_version')) {

            add_option('searchinside_version', $actualVersion);
        
        } else {

            if ($installed_version < $actualVersion) {

                update_option('searchinside_version', $actualVersion);
            }
        }

        flush_rewrite_rules();
    }

    /**
     * Hook plugin deactivation. Executed when deactivating the plugin.
     * 
     * @since 1.0.0
     *
     * @uses check_admin_referer()  → tests if the current request is valid 
     * @uses flush_rewrite_rules()  → remove rewrite rules and then recreate news
     */
    public function deactivation() {

        $plugin = isset($_REQUEST['plugin'] ) ? $_REQUEST['plugin'] : '';

        check_admin_referer("deactivate-plugin_{$plugin}");

        flush_rewrite_rules();
    }
}
