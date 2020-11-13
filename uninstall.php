<?php
/**
 * This file magically runs when the plugin is deleted
 *
 * Direct from https://developer.wordpress.org/plugins/the-basics/uninstall-methods/
 */

// if uninstall.php is not called by WordPress, die
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	die;
}

delete_option( 'disable_rest_api_options' );
