<?php
class DRA_Helpers {

	// Make sure this is called after wp-settings.php is loaded, or the `rest_get_server()` will throw 500's
	static function get_all_rest_routes() {
		$wp_rest_server     = rest_get_server();
		return array_keys( $wp_rest_server->get_routes() );
	}

	// Make sure this is called after wp-settings.php is loaded, or the `rest_get_server()` will throw 500's
	static function get_all_rest_namespaces() {
		$wp_rest_server     = rest_get_server();
		return $wp_rest_server->get_namespaces();
	}

	// Make sure this is called after wp-settings.php is loaded, or the `self::get_all_rest_routes()` will throw 500's
	static function build_routes_rule( $allowed_routes ) {

		// The full list of all routes in the system
		$all_routes = self::get_all_rest_routes();

		// Initialize our new rules
		$new_rules = array();

		// Loop through ALL routes, find out if any exist in the previously-existing rules. If so, they SHOULD be allowed. Default for everyone is false
		foreach ( $all_routes as $route ) {
			$new_value = false;
			if ( ! empty( $allowed_routes ) && in_array( $route, $allowed_routes ) ) {
				$new_value = true;
			}
			$new_rules[$route] = $new_value;
		}

		// Return full list of all known routes, with true/false values for whether they are allowed
		return $new_rules;
	}


}
