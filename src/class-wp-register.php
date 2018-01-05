<?php
/**
 * Register, minify and unify CSS and JavaScript resources.
 *
 * @author    Josantonius <hello@josantonius.com>
 * @package   Josantonius\WP_Register
 * @copyright 2017 - 2018 (c) Josantonius - WP_Register
 * @license   https://opensource.org/licenses/MIT - The MIT License (MIT)
 * @link      https://github.com/Josantonius/WP_Register
 * @since     1.0.0
 */

namespace Josantonius\WP_Register;

/**
 * Register CSS and JavaScript resources.
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
	 * @param string $type → script|style.
	 * @param array  $data → settings.
	 *
	 * @see https://github.com/Josantonius/WP_Register#add
	 *
	 * @return boolean
	 */
	public static function add( $type, $data = [] ) {

		$is_admin = is_admin();

		if ( self::validate( $type, $data, $is_admin ) ) {

			$hook = $is_admin ? 'admin_enqueue_scripts' : 'wp_enqueue_scripts';

			$method = __CLASS__ . "::add_{$type}s";

			if ( has_action( $hook, $method ) === false ) {
				add_action( $hook, $method );
			}

			return true;
		}

		return false;
	}

	/**
	 * Register, enqueue and localize scripts.
	 *
	 * The parameters "plugin_url" and "nonce" (based on script name) are added to each script.
	 */
	public static function add_scripts() {

		self::look_if_process_files( 'script' );

		foreach ( self::$data['script'] as $data ) {

			$params = [
				'plugin_url' => defined( 'WP_PLUGIN_URL' ) ? WP_PLUGIN_URL . '/' : '',
				'nonce'      => wp_create_nonce( $data['name'] ),
			];

			$data['params'] = array_merge( $data['params'], $params );

			wp_register_script(
				$data['name'],
				$data['url'],
				$data['deps'],
				$data['version'],
				$data['footer']
			);

			wp_enqueue_script( $data['name'] );

			wp_localize_script(
				$data['name'],
				$data['name'],
				$data['params']
			);
		}
	}

	/**
	 * Add register and enqueue styles.
	 */
	public static function add_styles() {

		self::look_if_process_files( 'style' );

		foreach ( self::$data['style'] as $data ) {

			wp_register_style(
				$data['name'],
				$data['url'],
				$data['deps'],
				$data['version'],
				$data['media']
			);

			wp_enqueue_style( $data['name'] );
		}
	}

	/**
	 * Check if a particular style or script has been added to be enqueued.
	 *
	 * @since 1.0.5
	 *
	 * @param string $type → script|style.
	 * @param string $name → script or style name.
	 *
	 * @return boolean
	 */
	public static function is_added( $type, $name ) {

		return isset( self::$data[ $type ][ $name ] );
	}

	/**
	 * Sets whether to merge the content of files into a single file.
	 *
	 * @since 1.0.4
	 *
	 * @param string  $id     → unique identifier for unified file.
	 * @param mixed   $params → path urls.
	 * @param boolean $minify → minimize file content.
	 *
	 * @return boolean true
	 */
	public static function unify( $id, $params, $minify = '' ) {

		self::$id     = $id;
		self::$unify  = $params;
		self::$minify = $minify;

		return true;
	}

	/**
	 * Remove before script or style have been registered.
	 *
	 * @since 1.0.1
	 *
	 * @param string $type → script|style.
	 * @param string $name → script or style name.
	 *
	 * @return boolean true
	 */
	public static function remove( $type, $name ) {

		if ( isset( self::$data[ $type ][ $name ] ) ) {

			unset( self::$data[ $type ][ $name ] );
		}

		return true;
	}

	/**
	 * Check if a particular style or script has been added to be enqueued.
	 *
	 * IMPORTANT: This method will be removed soon, use the 'is_added()' method instead.
	 *
	 * @since 1.0.4
	 *
	 * @deprecated 1.0.5
	 *
	 * @param string $type → script|style.
	 * @param string $name → script or style name.
	 *
	 * @return boolean
	 */
	public static function isAdded( $type, $name ) {
		return self::is_added( $type, $name );
	}

	/**
	 * Validate file register.
	 *
	 * @since 1.0.4
	 *
	 * @param string $type  → script|style.
	 * @param array  $data  → settings.
	 * @param bool   $admin → if is admin.
	 *
	 * @return boolean
	 */
	protected static function validate( $type, $data, $admin ) {

		$place = ( isset( $data['place'] ) ) ? $data['place'] : 'front';

		$place = $admin && 'admin' == $place || ! $admin && 'front' == $place;

		if ( ! $place || self::set_params( $type, $data ) === false ) {
			return false;
		}

		return true;
	}

	/**
	 * Set parameters.
	 *
	 * @since 1.0.4
	 *
	 * @param string $type → script|style.
	 * @param array  $data → settings.
	 *
	 * @return boolean
	 */
	protected static function set_params( $type, $data ) {

		if ( ! isset( $data['name'], $data['url'] ) ) {
			return false;
		}

		$data['deps']    = isset( $data['deps'] ) ? $data['deps'] : [];
		$data['version'] = isset( $data['version'] ) ? $data['version'] : false;

		switch ( $type ) {

			case 'script':
				$data['footer'] = isset( $data['footer'] ) ? $data['footer'] : true;
				$data['params'] = isset( $data['params'] ) ? $data['params'] : [];
				break;

			case 'style':
				$data['media'] = isset( $data['media'] ) ? $data['media'] : '';
				break;

			default:
		}

		self::$data[ $type ][ $data['name'] ] = $data;

		return true;
	}

	/**
	 * Look whether to process files to minify or unify files.
	 *
	 * @since 1.0.4
	 *
	 * @param string $type → script|style.
	 *
	 * @return boolean
	 */
	protected static function look_if_process_files( $type ) {

		if ( is_string( self::$unify ) || isset( self::$unify[ "{$type}s" ] ) ) {
			return self::unify_files(
				self::prepare_files( $type )
			);
		}
	}

	/**
	 * Check files and prepare paths and urls.
	 *
	 * @since 1.0.4
	 *
	 * @param string $type → script|style.
	 *
	 * @return array|false → paths, urls and outdated files.
	 */
	protected static function prepare_files( $type ) {

		$params['type']   = $type;
		$params['routes'] = self::get_routes_to_folder( $type );

		self::get_processed_files();

		foreach ( self::$data[ $type ] as $id => $file ) {

			$path = self::get_path_from_url( $file['url'] );

			$params['files'][ $id ] = basename( $file['url'] );
			$params['urls'][ $id ]  = $file['url'];
			$params['paths'][ $id ] = $path;

			if ( is_file( $path ) && self::is_modified_file( $path ) ) {
				unset( $params['urls'][ $id ] );
				continue;
			}

			$path = $params['routes']['path'] . $params['files'][ $id ];

			if ( is_file( $path ) ) {
				if ( self::is_modified_hash( $file['url'], $path ) ) {
					continue;
				}
				$params['paths'][ $id ] = $path;

			} elseif ( self::is_external_url( $file['url'] ) ) {
				continue;
			}

			unset( $params['urls'][ $id ] );
		}

		return $params;
	}

	/**
	 * Get path|url to the minimized file.
	 *
	 * @since 1.0.4
	 *
	 * @param string $type → scripts|styles.
	 *
	 * @return array → url|path to minimized file
	 */
	protected static function get_routes_to_folder( $type ) {

		$url = isset( self::$unify[ "{$type}s" ] ) ? self::$unify[ "{$type}s" ] : self::$unify;

		return [
			'url'  => $url,
			'path' => self::get_path_from_url( $url ),
		];
	}

	/**
	 * Obtain information from processed files.
	 *
	 * @since 1.0.4
	 *
	 * @return void
	 */
	protected static function get_processed_files() {

		self::$files = get_option( 'wp-register-files', [] );
	}

	/**
	 * Get path from url.
	 *
	 * @since 1.0.4
	 *
	 * @param string $url → file url.
	 *
	 * @return string → filepath
	 */
	protected static function get_path_from_url( $url ) {

		return $_SERVER['DOCUMENT_ROOT'] . parse_url( $url, PHP_URL_PATH );
	}

	/**
	 * Check if the file was modified.
	 *
	 * @since 1.0.4
	 *
	 * @param string $filepath → path of the file.
	 *
	 * @return boolean
	 */
	protected static function is_modified_file( $filepath ) {

		$actual = filemtime( $filepath );

		$last = isset( self::$files[ $filepath ] ) ? self::$files[ $filepath ] : 0;

		if ( $actual !== $last ) {

			self::$files[ $filepath ] = $actual;

			self::$changes = true;

			return self::$changes;
		}

		return false;
	}

	/**
	 * Check if it matches the file hash.
	 *
	 * @since 1.0.4
	 *
	 * @param string $url  → external url.
	 * @param string $path → internal file path.
	 *
	 * @return boolean
	 */
	protected static function is_modified_hash( $url, $path ) {

		if ( self::is_external_url( $url ) ) {
			if ( sha1_file( $url ) !== sha1_file( $path ) ) {
				self::$changes = true;
				return self::$changes;
			}
		}

		return false;
	}

	/**
	 * Check if it's an external file.
	 *
	 * @since 1.0.4
	 *
	 * @param string $url → file url.
	 *
	 * @return boolean
	 */
	protected static function is_external_url( $url ) {

		return ( strpos( $url, get_site_url() ) === false );
	}

	/**
	 * Unify files.
	 *
	 * @since 1.0.4
	 *
	 * @param array  $params → paths and urls of files to unify.
	 * @param string $data   → initial string.
	 *
	 * @return boolean true
	 */
	protected static function unify_files( $params, $data = '' ) {

		$type      = $params['type'];
		$routes    = $params['routes'];
		$extension = ( 'style' == $type ) ? '.css' : '.js';
		$hash      = sha1( implode( '', $params['files'] ) );
		$min_file  = $routes['path'] . $hash . $extension;

		if ( ! is_file( $min_file ) || self::$changes ) {
			foreach ( $params['paths'] as $id => $path ) {
				if ( isset( $params['urls'][ $id ] ) ) {
					$url   = $params['urls'][ $id ];
					$path  = $routes['path'] . $params['files'][ $id ];
					$data .= self::save_external_file( $url, $path );
				}

				$data .= file_get_contents( $path );
			}

			$data = ( self::$minify ) ? self::compress_files( $data ) : $data;

			self::save_file( $min_file, $data );
		}

		self::set_processed_files();

		return self::set_new_params( $type, $hash, $routes['url'], $extension );
	}

	/**
	 * Save external file.
	 *
	 * @since 1.0.4
	 *
	 * @param string $url  → external url.
	 * @param string $path → internal file path.
	 *
	 * @return string → file content or empty
	 */
	protected static function save_external_file( $url, $path ) {

		$data = file_get_contents( $url );

		return ( $data && self::save_file( $path, $data ) ) ? $data : '';
	}

	/**
	 * File minifier.
	 *
	 * @since 1.0.4
	 *
	 * @author powerbuoy (https://github.com/powerbuoy)
	 *
	 * @param string $content → file content.
	 *
	 * @return array → unified parameters
	 */
	protected static function compress_files( $content ) {

		$var = array( "\r\n", "\r", "\n", "\t", '  ', '    ', '    ' );

		$content = preg_replace( '!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $content );

		$content = str_replace( $var, '', $content );
		$content = str_replace( '{ ', '{', $content );
		$content = str_replace( ' }', '}', $content );
		$content = str_replace( '; ', ';', $content );

		return $content;
	}

	/**
	 * Save file.
	 *
	 * @since 1.0.4
	 *
	 * @param string $path → internal file path.
	 * @param string $data → file content.
	 *
	 * @return boolean
	 */
	protected static function save_file( $path, $data ) {

		self::create_directory_from_file( $path );

		return file_put_contents( $path, $data );
	}

	/**
	 * Create directory where external/unified/minimized files will be saved.
	 *
	 * @since 1.0.4
	 *
	 * @param string $file → filepath.
	 *
	 * @return boolean
	 */
	protected static function create_directory_from_file( $file ) {

		$path = dirname( $file );

		if ( ! is_dir( $path ) ) {
			return mkdir( $path, 0777, true );
		}

		return true;
	}

	/**
	 * Set information from processed files.
	 *
	 * @since 1.0.4
	 *
	 * @return void
	 */
	protected static function set_processed_files() {

		if ( self::$changes ) {
			update_option( 'wp-register-files', self::$files );
		}
	}

	/**
	 * Set new parameters for the unified file.
	 *
	 * @since 1.0.4
	 *
	 * @param string $type      → script|style.
	 * @param string $hash      → filename hash.
	 * @param string $url       → path url.
	 * @param string $extension → file extension.
	 *
	 * @return boolean true
	 */
	protected static function set_new_params( $type, $hash, $url, $extension ) {

		$data = [

			'name'    => self::$id,
			'url'     => $url . $hash . $extension,
			'deps'    => self::unify_params( $type, 'deps' ),
			'version' => self::unify_params( $type, 'version', '1.0.0' ),
		];

		switch ( $type ) {
			case 'style':
				$data['media'] = self::unify_params( $type, 'media', 'all' );
				break;

			case 'script':
				$data['params'] = self::unify_params( $type, 'params' );
				$data['footer'] = self::unify_params( $type, 'footer', false );

				$data['params']['nonce'] = wp_create_nonce( self::$id );
				break;

			default:
		}

		self::$data[ $type ] = [ $data['name'] => $data ];

		return true;
	}

	/**
	 * Obtain all the parameters of a particular field and unify them.
	 *
	 * @since 1.0.4
	 *
	 * @param string $type    → script|style.
	 * @param string $field   → field to unify.
	 * @param string $default → default param.
	 *
	 * @return array → unified parameters
	 */
	protected static function unify_params( $type, $field, $default = '' ) {

		$data = array_column( self::$data[ $type ], $field );

		switch ( $field ) {
			case 'media':
			case 'footer':
			case 'version':
				foreach ( $data as $key => $value ) {
					if ( $data[0] !== $value ) {
						return $default;
					}
				}

				return ( isset( $data[0] ) && $data[0] ) ? $data[0] : $default;

			default:
				$params = [];

				foreach ( $data as $key => $value ) {
					$params = array_merge( $params, $value );
				}

				return array_unique( $params );
		}
	}
}
