<?php
/*
Plugin Name: 	Laravel WordPress
Plugin URI:     https://github.com/StepanPakholok/laravel-wordpress
Description: 	Plugin to manage whatever by using powerful Laravel framework inside.
Author:      	Stepan Pakholok
Author URI:     https://github.com/StepanPakholok
Version: 		1.0
Text Domain:    laravel-wordpress
Domain Path:    languages/
*/

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Access denied.' );
}

/*
 * Turns on php output buffering on init to attach Laravel cookies later
 */
add_action( 'init', 'add_ob_start_for_laravel' );
function add_ob_start_for_laravel() {
	ob_start();
}

/**
 * Loads plugin l10n - uncomment if needed
 */
// add_action( 'plugins_loaded', 'laravel_wordpress_load_textdomain' );
// function laravel_wordpress_load_textdomain() {
//  load_plugin_textdomain( 'laravel-wordpress', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
// }

/**
 * Adds new WP Capability for plugin purposes
 */
add_action( 'admin_init', 'init_wordpress_capability' );
function init_wordpress_capability() {
	$role = get_role( 'administrator' );
	$role->add_cap( 'use_laravel_wordpress' );
}

/**
 * Laravel initialization as a plugin under the WP Admin panel
 */
add_action( 'admin_menu', 'add_wordpress_menus' );
function add_wordpress_menus() {
	if ( current_user_can( 'use_laravel_wordpress' ) ) {
		add_menu_page( __( 'Laravel', 'laravel-wordpress' ), __( 'Laravel', 'laravel-wordpress' ),
			'use_laravel_wordpress', 'laravel_wordpress', 'process_through_laravel', 'dashicons-share-alt', 100 );
	}
}

/**
 * Laravel AJAX initialization
 */
add_action( 'wp_ajax_laravel_wordpress_ajax', 'laravel_wordpress_ajax_callback' );
function laravel_wordpress_ajax_callback() {
	process_through_laravel();
}

/**
 *  Laravel integration helper
 */
function process_through_laravel() {
	$laravelUri = '/';
	if ( isset( $_REQUEST['path'] ) ) {
		$path  = urldecode( $_REQUEST['path'] );
		$index = strpos( $path, '?' );

		$laravelUri = $path;

		if ( $index !== false ) {
			$_SERVER['QUERY_STRING'] = substr( $path, 1 + $index );
			parse_str( $_SERVER['QUERY_STRING'], $variables );

			foreach ( $variables as $k => $v ) {
				$_GET['$k'] = '$v';
			}
		}
	}

	$dir = 'laravel/bootstrap/';
	// make sure that the framework installed
	if ( ! laravel_installation_exists( $dir ) ) {
		show_laravel_installation_instructions();

		return;
	}

	// the code below is inherited from Laravel application entry point (public/index.php)
	require $dir . 'autoload.php';
	$app = require_once $dir . 'app.php';

	$_SERVER['SCRIPT_FILENAME'] = $app->publicPath() . '/index.php';
	$_SERVER['REQUEST_URI']     = $laravelUri;
	$_SERVER['SCRIPT_NAME']     = $_SERVER['PHP_SELF'] = '/index.php';

	$kernel = $app->make( Illuminate\Contracts\Http\Kernel::class );

	$response = $kernel->handle(
		$request = Illuminate\Http\Request::capture()
	);

	$response->send();
	$kernel->terminate( $request, $response );

	// flush output buffer started on init to allow Laravel set cookies properly
	ob_end_flush();
}

function laravel_installation_exists( $dir ) {
	if ( file_exists( dirname( __FILE__ ) . DIRECTORY_SEPARATOR . $dir ) ) {
		return true;
	}

	return false;
}

function show_laravel_installation_instructions() {
	$html = <<<HTML
		<h3>
			Laravel Installation required
		</h3>
		<p>
			Please visit <a href="https://github.com/StepanPakholok/laravel-wordpress">Plugin Github page</a>
			for more details.
		</p>
HTML;

	echo $html;
}

require_once 'laravel-helpers.php';
