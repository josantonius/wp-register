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

use Josantonius\File\File;

createFiles();

function createFiles() {

	File::createDir($css = WP_CORE_DIR . 'wp-content/themes/tests/css/');

	File::createDir($js = WP_CORE_DIR . 'wp-content/themes/tests/js/');

    if (!File::exists($css . 'editor-style.css')) {
    	
    	file_put_contents($css . 'editor-style.css', "
			
			body, h1, h2, h3, h4, h5, h6 {
			 font-family: 'Open Sans', sans-serif;
			}

			body {
			 font-size: 1.6em;
			 line-height: 1.6;
			}
    	");
    }

    if (!File::exists($css . 'style.css')) {
    	
    	file_put_contents($css . 'style.css', "
			
			body, h1, h2, h3, h4, h5, h6 {
			 font-family: 'Open Sans', sans-serif;
			}

			body {
			 font-size: 1.6em;
			 line-height: 1.6;
			}
    	");
    }

    if (!File::exists($js . 'html5.js')) {
    	
    	file_put_contents($js . 'html5.js', "
			
			function myFunction() {

			    document.getElementById('myCheck').click();
			}
    	");
    }

    if (!File::exists($js . 'navigation.js')) {
    	
    	file_put_contents($js . 'navigation.js', "
			
			$('p').click(function(){
			    alert('The paragraph was clicked.');
			});
    	");
    }
}