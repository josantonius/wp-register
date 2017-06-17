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
    protected static $data = [];

    /**
     * Add scripts or styles.
     *
     * @since 1.0.0
     *
     * @param string  $type → script | style
     * @param array   $data → settings
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

        $data['name']    = (isset($data['name']))    ? $data['name']    : '';
        $data['url']     = (isset($data['url']))     ? $data['url']     : '';
        $data['deps']    = (isset($data['deps']))    ? $data['deps']    : [];
        $data['version'] = (isset($data['version'])) ? $data['version'] :  0;
        $data['media']   = (isset($data['media']))   ? $data['media']   : '';

        $data['name'] = preg_replace('/[^A-Za-z0-9]+/', '', $data['name']);
        
        if ($type === 'script') {

            $data['footer'] = (isset($data['footer'])) ? $data['footer'] :  1;
            $data['params'] = (isset($data['params'])) ? $data['params'] : [];
        }

        $isAdmin = is_admin();

        $place = (isset($data['place'])) ? $data['place'] : 'front';

        if ($isAdmin && $place == 'admin' || !$isAdmin && $place == 'front') {

            self::$data[$type][$data['name']] = $data;

        } else {

            return false;
        }

        $type = ucfirst($type);

        $hook = ($isAdmin) ? 'admin_enqueue_scripts' : 'wp_enqueue_scripts';

        if (!isset(self::$data[$type][$hook])) {

            self::$data[$type][$hook] = true;

            add_action($hook, __CLASS__ .'::add' . $type . 's');
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
    public static function addScripts() {
        
        foreach (self::$data['script'] as $data) {

            $params = [
                'pluginUrl' => WP_PLUGIN_URL . '/',
                'nonce'     => wp_create_nonce($data['name'] . '-nonce')
            ];

            $data['params'] = array_merge($data['params'], $params);

            wp_register_script(

                $data['name'], 
                $data['url'], 
                $data['deps'], 
                $data['version'], 
                $data['footer']
            );

            wp_enqueue_script($data['name']);

            wp_localize_script(

                $data['name'], 
                $data['name'], 
                $data['params']
            );
        }
    }

    /**
     * Add styles.
     *
     * @since 1.0.0
     *
     * @uses wp_enqueue_script() → enqueue a script
     * @uses wp_register_style() → register a CSS stylesheet
     */
    public static function addStyles() {

        foreach (self::$data['style'] as $data) {

            wp_register_style(

                $data['name'], 
                $data['url'], 
                $data['deps'], 
                $data['version'], 
                $data['media']
            );

            wp_enqueue_style($data['name']);
        }
    }

    /**
     * Check if a particular style or script has been added to be enqueued.
     *
     * @since 1.0.1
     *
     * @param string $type → script | style
     * @param array  $name → script or style name
     *
     * @return boolean
     */
    public static function isSet($type, $name) {

        return (in_array($name, self::$data[$type][$name])) ? true : false;
    }

    /**
     * Remove before script or style have been enqueued.
     *
     * @since 1.0.1
     *
     * @param string $type → script | style
     * @param array  $name → script or style name
     */
    public static function remove($type, $name) {

        unset(self::$data[$type][$name]);
    }
}
