<?php 
/**
 * Register, minify and unify CSS and JavaScript resources.
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
     * Unique identifier for unified file.
     *
     * @since 1.0.4
     *
     * @var string
     */
    protected static $id;

    /**
     * Settings to register styles or scripts.
     *
     * @since 1.0.0
     *
     * @var array
     */
    protected static $data = [];

    /**
     * Files saved information.
     *
     * @since 1.0.4
     *
     * @var array
     */
    protected static $files;

    /**
     * True if file is outdated.
     *
     * @since 1.0.4
     *
     * @var boolean
     */
    protected static $changes = false;

    /**
     * Unify files.
     *
     * @since 1.0.4
     *
     * @var string|boolean
     */
    protected static $unify = false;

    /**
     * Minify files.
     *
     * @since 1.0.4
     *
     * @var boolean
     */
    protected static $minify = false;

    /**
     * Add scripts or styles.
     *
     * @since 1.0.0
     *
     * @param string  $type → script|style
     * @param array   $data → settings
     *
     *        string  $data['name']    → unique id        (required)
     *        string  $data['url']     → url to file      (required)
     *        string  $data['place']   → admin|front      (optional)
     *        array   $data['deps']    → dependences      (optional)
     *        string  $data['version'] → version          (optional)
     *        boolean $data['footer']  → attach in footer (optional | scripts)
     *        array   $data['params']  → available in JS  (optional | scripts)
     *        string  $data['media']   → media            (optional | styles)
     *
     * @return boolean
     */
    public static function add($type, $data = []) {

        $isAdmin = is_admin();

        if (self::validate($type, $data, $isAdmin)) {

            $type = ucfirst($type);

            $hook = $isAdmin ? 'admin_enqueue_scripts' : 'wp_enqueue_scripts';

            if (!isset(self::$data[$type][$hook])) {

                self::$data[$type][$hook] = true;

                add_action($hook, __CLASS__ .'::add' . $type . 's');
            }

            return true;
        }
        
        return false;
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
     *
     * @return void
     */
    public static function addScripts() {

        self::lookIfProcessFiles('script');
        
        foreach (self::$data['script'] as $data) {

            $pluginUrl = defined('WP_PLUGIN_URL') ? WP_PLUGIN_URL . '/' : '';

            $params = [
                'pluginUrl' => $pluginUrl,
                'nonce'     => wp_create_nonce($data['name'])
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
     *
     * @return void
     */
    public static function addStyles() {

        self::lookIfProcessFiles('style');

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
     * @since 1.0.4
     *
     * @param string $type → script|style
     * @param string $name → script or style name
     *
     * @return boolean
     */
    public static function isAdded($type, $name) {

        if (isset(self::$data[$type][$name])) {

            return true;
        }

        return false;
    }

    /**
     * Sets whether to merge the content of files into a single file.
     *
     * @since 1.0.4
     *
     * @param string  $id     → unique identifier for unified file
     * @param mixed   $params → path urls
     * @param boolean $minify → minimize file content
     *
     * @return boolean true
     */
    public static function unify($id, $params, $minify = false) {

        self::$id = $id;

        self::$unify = $params;

        self::$minify = $minify;

        return true;
    }

    /**
     * Remove before script or style have been registered.
     *
     * @since 1.0.1
     *
     * @param string $type → script|style
     * @param string $name → script or style name
     *
     * @return boolean true
     */
    public static function remove($type, $name) {
        
        if (isset(self::$data[$type][$name])) {

            unset(self::$data[$type][$name]);

            return true;
        }

        return false;
    }

    /**
     * Validate file register.
     *
     * @since 1.0.4
     *
     * @param string $type  → script|style
     * @param array  $data  → settings
     * @param bool   $admin → if is admin
     *
     * @return boolean
     */
    protected static function validate($type, $data, $admin) {

        $place = (isset($data['place'])) ? $data['place'] : 'front';

        $place = $admin && $place == 'admin' || !$admin && $place == 'front';

        if (!$place || self::setParams($type, $data) === false) {

            return false;
        }

        return true;
    }

    /**
     * Set parameters.
     *
     * @since 1.0.4
     *
     * @param string $type → script|style
     * @param array  $data → settings
     *
     * @return boolean
     */
    protected static function setParams($type, $data) {

        if (!isset($data['name'], $data['url'])) { 

            return false;
        }

        $data['name'] = $data['name'];

        $data['deps'] = isset($data['deps']) ? $data['deps'] : [];
        
        $data['version'] = isset($data['version']) ? $data['version'] : false;
        
        if ($type === 'script') {

            $data['footer'] = isset($data['footer']) ? $data['footer'] : true;
            $data['params'] = isset($data['params']) ? $data['params'] : [];
        
        } else if ($type === 'style') {

            $data['media'] = isset($data['media']) ? $data['media'] : '';
        }

        self::$data[$type][$data['name']] = $data;

        return true;
    }

    /**
     * Look whether to process files to minify or unify files.
     *
     * @since 1.0.4
     *
     * @param string $type → script|style
     *
     * @return boolean
     */
    protected static function lookIfProcessFiles($type) {

        if (is_string(self::$unify) || isset(self::$unify[$type . 's'])) {
        
            return self::unifyFiles(

                self::prepareFiles($type)
            );
        }
    }

    /**
     * Check files and prepare paths and urls.
     *
     * @since 1.0.4
     *
     * @param string $type → script|style
     *
     * @return array|false → Paths, urls and outdated files
     */
    protected static function prepareFiles($type) {

        $params['type'] = $type;

        $params['routes'] = self::getRoutesToFolder($type);

        self::getProcessedFiles();

        foreach(self::$data[$type] as $id => $file) {

            $path = self::getPathFromUrl($file['url']);

            $params['files'][$id] = basename($file['url']);
            $params['urls'][$id]  = $file['url'];
            $params['paths'][$id] = $path;
            
            if (is_file($path) && self::isModifiedFile($path)) {

                unset($params['urls'][$id]);

                continue;
            }

            $path = $params['routes']['path'] . $params['files'][$id];

            if (is_file($path)) {

                if (self::isModifiedHash($file['url'], $path)) {

                    continue;
                }
                
                $params['paths'][$id] = $path;
            
            } else if (self::isExternalUrl($file['url'])) {

                continue;
            }

            unset($params['urls'][$id]);
        }

        return $params;
    }

    /**
     * Get path|url to the minimized file.
     *
     * @since 1.0.4
     *
     * @param string $type     → scripts|styles
     * @param string $filename → filename
     *
     * @return array → url|path to minimized file
     */
    protected static function getRoutesToFolder($type) {

        $type = $type . 's';

        $url = isset(self::$unify[$type]) ? self::$unify[$type] : self::$unify;

        return ['url'  => $url, 'path' => self::getPathFromUrl($url)];
    }

    /**
     * Obtain information from processed files.
     *
     * @since 1.0.4
     *
     * @return void
     */
    protected static function getProcessedFiles() {

        if (!$data = get_option('wp_register_files')) {

            add_option('wp_register_files', $data = '[]');
        }

        self::$files = json_decode($data, true);
    }

    /**
     * Get path from url.
     *
     * @since 1.0.4
     *
     * @param string $url → file url
     *
     * @return string → filepath
     */
    protected static function getPathFromUrl($url) {

        return $_SERVER['DOCUMENT_ROOT'] . parse_url($url, PHP_URL_PATH);
    }

    /**
     * Check if the file was modified.
     *
     * @since 1.0.4
     *
     * @param string $filepath → path of the file
     *
     * @return boolean
     */
    protected static function isModifiedFile($filepath) {

        $actual = filemtime($filepath);

        $filepath = sha1($filepath);

        $last = isset(self::$files[$filepath]) ? self::$files[$filepath] : 0;

        if ($actual > $last) {

            self::$files[$filepath] = $actual;

            return self::$changes = true;
        }

        return false;
    }

    /**
     * Check if it matches the file hash.
     *
     * @since 1.0.4
     *
     * @param string $url  → external url
     * @param string $path → internal file path
     *
     * @return boolean
     */
    protected static function isModifiedHash($url, $path) {

        if (self::isExternalUrl($url)) {

            if (sha1_file($url) !== sha1_file($path)) {

                return self::$changes = true;
            }
        }

        return false;
    }

    /**
     * Check if it's an external file.
     *
     * @since 1.0.4
     *
     * @param string $url → file url
     *
     * @return boolean
     */
    protected static function isExternalUrl($url) {

        return (strpos($url, get_site_url()) === false);
    }

    /**
     * Unify files.
     *
     * @since 1.0.4
     *
     * @param array  $params → paths and urls of files to unify
     * @param string $data   → initial string
     *
     * @return boolean true
     */
    protected static function unifyFiles($params, $data = '') {

        $type = $params['type'];

        $routes = $params['routes'];

        $extension = ($type == 'style') ? '.css' : '.js';

        $hash = sha1(implode('', $params['files']));

        $minFile = $routes['path'] . $hash . $extension;

        if (!is_file($minFile) || self::$changes == true) {

            foreach ($params['paths'] as $id => $path) {

                if (isset($params['urls'][$id])) {  

                    $url = $params['urls'][$id];

                    $path = $routes['path'] . $params['files'][$id];

                    $data .= self::saveExternalFile($url, $path);
                }

                $data .= file_get_contents($path);
            }

            $data = (self::$minify) ? self::compressFiles($data) : $data;

            self::saveFile($minFile, $data);
        }

        self::setProcessedFiles();

        return self::setNewParams($type, $hash, $routes['url'], $extension);
    }

    /**
     * Save external file.
     *
     * @since 1.0.4
     *
     * @param string $url  → external url
     * @param string $path → internal file path
     *
     * @return string → file content or empty
     */
    protected static function saveExternalFile($url, $path) {

        if ($data = file_get_contents($url)) {

            return (self::saveFile($path, $data)) ? $data : '';
        }

        return '';
    }

    /**
     * File minifier.
     *
     * @since 1.0.4
     *
     * @author powerbuoy (https://github.com/powerbuoy)
     *
     * @param string $content → file content
     *
     * @return array → unified parameters
     */
    protected static function compressFiles($content) {

        $var = array("\r\n", "\r", "\n", "\t", '  ', '    ', '    ');

        $content = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $content);

        $content = str_replace($var, '',  $content);
        $content = str_replace('{ ', '{', $content);
        $content = str_replace(' }', '}', $content);
        $content = str_replace('; ', ';', $content);

        return $content;
    }

    /**
     * Save file.
     *
     * @since 1.0.4
     *
     * @param string $path → internal file path
     * @param string $data → file content
     *
     * @return boolean
     */
    protected static function saveFile($path, $data) {

        self::createDirectoryFromFile($path);

        return file_put_contents($path, $data);
    }

    /**
     * Create directory where external/unified/minimized files will be saved.
     *
     * @since 1.0.4
     *
     * @param string $url → path of the file
     *
     * @return boolean
     */
    protected static function createDirectoryFromFile($file) {

        $path = dirname($file);

        if (!is_dir($path)) {

            return mkdir($path, 0777, true);
        }

        return true;
    }

    /**
     * Set information from processed files.
     *
     * @since 1.0.4
     *
     * @return array
     */
    protected static function setProcessedFiles() {

        if (self::$changes) {

            update_option('wp_register_files', json_encode(self::$files));
        }
    }

    /**
     * Set new parameters for the unified file.
     *
     * @since 1.0.4
     *
     * @param string $type      → script|style
     * @param string $hash      → filename hash
     * @param string $url       → path url
     * @param string $extension → file extension
     *
     * @return boolean true
     */
    protected static function setNewParams($type, $hash, $url, $extension) {

        $data = [

            'name'    => self::$id,
            'url'     => $url . $hash . $extension,
            'deps'    => self::unifyParams($type, 'deps'),
            'version' => self::unifyParams($type, 'version', '1.0.0')
        ];

        if ($type === 'style') {

            $data['media'] = self::unifyParams($type, 'media', 'all');

        } else if ($type === 'script') {

            $data['params'] = self::unifyParams($type, 'params');

            $data['params']['nonce'] = wp_create_nonce(self::$id);

            $data['footer'] = self::unifyParams($type, 'footer',  false);
        }

        self::$data[$type] = [$data['name'] => $data];

        return true;
    }

    /**
     * Obtain all the parameters of a particular field and unify them.
     *
     * @since 1.0.4
     *
     * @param string $type    → script|style
     * @param string $field   → field to unify
     * @param string $default → default param
     *
     * @return array → unified parameters
     */
    protected static function unifyParams($type, $field, $default = '') {

        $data = array_column(self::$data[$type], $field);

        switch ($field) {

            case 'media':
            case 'footer':
            case 'version':
        
                foreach ($data as $key => $value) {
                    
                    if ($data[0] !== $value) {

                        return $default;
                    }
                }

                return (isset($data[0]) && $data[0]) ? $data[0] : $default;
            
            default:

                $params = [];

                foreach ($data as $key => $value) {

                    $params = array_merge($params, $value);
                }

                return array_unique($params);
        }
    }
}
