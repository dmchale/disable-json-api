<?php
/**
 * Plugin Name: Disable REST API
 * Plugin URI: http://www.binarytemplar.com/disable-json-api
 * Description: Disable the use of the REST API on your website to anonymous users. You can optionally enable select endpoints if you wish. Now with support for User Roles!
 * Version: 1.7
 * Author: Dave McHale
 * Author URI: http://www.binarytemplar.com
 * Text Domain: disable-json-api
 * Domain Path: /languages
 * License: GPL2+
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// i18n
add_action( 'init', 'disable_rest_api_load_textdomain' );
function disable_rest_api_load_textdomain() {
	load_plugin_textdomain( 'disable-json-api', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
}

// Requirements check, to cleanly handle failure of WP/PHP version requirements
include( dirname( __FILE__ ) . '/classes/requirements-check.php' );

$dra_requirements_check = new DRA_Requirements_Check( array(
	'title' => 'Disable REST API',
	'php'   => '5.3',
	'wp'    => '4.4',
	'file'  => __FILE__,
) );

// Only load plugin if we pass minimum requirements
if ( $dra_requirements_check->passes() ) {

	// Remove REST API info from head and headers
	remove_action( 'xmlrpc_rsd_apis', 'rest_output_rsd' );
	remove_action( 'wp_head', 'rest_output_link_wp_head', 10 );
	remove_action( 'template_redirect', 'rest_output_link_header', 11 );

	// WordPress 4.7+ disables the REST API via authentication short-circuit.
	// For versions of WordPress < 4.7, disable the REST API via filters
	if ( version_compare( get_bloginfo( 'version' ), '4.7', '>=' ) ) {

		// Load in extra classes
		require_once( plugin_dir_path( __FILE__ ) . 'classes/helpers.php' );

		// Only load admin classes if in admin area
		if ( is_admin() ) {
			require_once( plugin_dir_path( __FILE__ ) . 'classes/admin.php' );
		}

		// Load the primary Disable_REST_API class
		require_once( plugin_dir_path( __FILE__ ) . 'classes/disable-rest-api.php' );
		new Disable_REST_API( __FILE__ );

	} else {
		require_once( plugin_dir_path( __FILE__ ) . 'functions/legacy.php' );
		DRA_Disable_Via_Filters();
	}

}

unset( $dra_requirements_check );
