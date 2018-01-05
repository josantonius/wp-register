<?php
/**
 * Register, minify and unify CSS and JavaScript resources.
 *
 * @author    Josantonius <hello@josantonius.com>
 * @package   Josantonius\WP_Register
 * @copyright 2017 - 2018 (c) Josantonius - WP_Register
 * @license   https://opensource.org/licenses/MIT - The MIT License (MIT)
 * @link      https://github.com/Josantonius/WP_Register
 * @since     1.0.4
 */

create_files();

/**
 * Create files for testing environment.
 */
function create_files() {

	$css = WP_CORE_DIR . 'wp-content/themes/tests/css/';
	$js  = WP_CORE_DIR . 'wp-content/themes/tests/js/';

	if ( ! is_dir( $css ) ) {
		@mkdir( $css, 0777, true );
	}

	if ( ! is_dir( $js ) ) {
		@mkdir( $js, 0777, true );
	}

	if ( ! file_exists( $css . 'editor-style.css' ) ) {

		file_put_contents(
			$css . 'editor-style.css', "
			
			body, h1, h2, h3, h4, h5, h6 {
			 font-family: 'Open Sans', sans-serif;
			}

			body {
			 font-size: 1.6em;
			 line-height: 1.6;
			}
    	"
		);
	}

	if ( ! file_exists( $css . 'style.css' ) ) {

		file_put_contents(
			$css . 'style.css', "
			
			body, h1, h2, h3, h4, h5, h6 {
			 font-family: 'Open Sans', sans-serif;
			}

			body {
			 font-size: 1.6em;
			 line-height: 1.6;
			}
    	"
		);
	}

	if ( ! file_exists( $js . 'html5.js' ) ) {

		file_put_contents(
			$js . 'html5.js', "
			
			function myFunction() {

			    document.getElementById('myCheck').click();
			}
    	"
		);
	}

	if ( ! file_exists( $js . 'navigation.js' ) ) {

		file_put_contents(
			$js . 'navigation.js', "
			
			$('p').click(function(){
			    alert('The paragraph was clicked.');
			});
    	"
		);
	}
}
