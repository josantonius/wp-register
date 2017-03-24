<?php 
/**
 * Register CSS and JavaScript resources.
 * 
 * @author     Josantonius - hello@josantonius.com
 * @copyright  Copyright (c) 2017
 * @license    https://opensource.org/licenses/MIT - The MIT License (MIT)
 * @link       https://github.com/Josantonius/WP_Register
 * @since      1.0.0
 */

namespace Josantonius\WP_Register;

/**
 * Register css and JavaScript resources.
 *
 * @since 1.0.0
 */
class WP_Register {

    /**
     * Settings to register styles or scripts.
     *
     * @since 1.0.0
     *
     * @var array
     */
    protected static $data;

    /**
     * Add scripts or styles.
     *
     * @since 1.0.0
     *
     * @param string  $type → script | style
     * @param array   $data → extra settings
     *
     *        string  $data['name']    = 'searchinside';
     *        string  $data['url']     = 'plugins/name/js/searchinside.js';
     *        string  $data['place']   = 'front';    (optional)
     *        array   $data['deps']    = ['jquery']; (optional)
     *        string  $data['version'] = '1.0.0';    (optional)
     *        boolean $data['footer']  = true;       (optional - for scripts)
     *        array   $data['params']  = [];         (optional - for scripts)
     *        string  $data['media']   = 'all';      (optional - for styles)
     *
     * @return boolean
     */
    public static function add($type, $data = []) {

        $type = ucfirst($type);   

        $data['name']    = (isset($data['name']))    ? $data['name']    : '';
        $data['url']     = (isset($data['url']))     ? $data['url']     : '';
        $data['deps']    = (isset($data['deps']))    ? $data['deps']    : [];
        $data['version'] = (isset($data['version'])) ? $data['version'] : 0;
        $data['media']   = (isset($data['media']))   ? $data['media']   : '';

        if ($type === 'script') {

            $params = [
                'pliginUrl' => WP_PLUGIN_URL,
                'nonce'     => wp_create_nonce(self::$data['name'] . '-nonce')
            ];

            $data['footer'] = (isset($data['footer'])) ? $data['footer'] : 1;
            $data['params'] = (isset($data['params'])) ? $data['params'] : [];

            $data['params'] = array_merge($data['params'], $params);
        }

        self::$data = $data;

        $isAdmin = is_admin();

        $place = (isset($data['place'])) ? $data['place'] : 'front';

        if ($isAdmin && $place === 'admin') {

            #add_action('wp_enqueue_scripts', array($instance, $type));
        
        } else if (!$isAdmin && $place === 'front') {) {

            add_action('wp_enqueue_scripts', __CLASS__ .'::add'.$type);
        
        } else {

            return false;
        }

        return true;
    }

    /**
     * Add scripts.
     *
     * @since 1.0.0
     *
     * @uses wp_register_script() → registers a script
     * @uses wp_enqueue_script()  → enqueue a script
     * @uses wp_localize_script() → localizes a registered script
     * @uses admin_url()          → URL to the admin area for the current site
     * @uses wp_create_nonce()    → creates a cryptographic token
     */
    protected static function addScript() {

        wp_register_script(

            self::$data['name'], 
            self::$data['url'], 
            self::$data['deps'], 
            self::$data['version'], 
            self::$data['footer']
        );

        wp_enqueue_script(self::$data['name']);

        wp_localize_script(

            self::$data['name'], 
            self::$data['name'], 
            self::$data['params']
        );
    }

    /**
     * Add styles.
     *
     * @since 1.0.0
     *
     * @uses wp_enqueue_script() → enqueue a script
     * @uses wp_register_style() → register a CSS stylesheet
     */
    protected static function addStyle() {

        wp_register_style(

            self::$data['name'], 
            self::$data['url'], 
            self::$data['deps'], 
            self::$data['version'], 
            self::$data['media']
        );

        wp_enqueue_style(self::$data['name']);
    }
}
