<?php
class DRA_Helpers {

	/**
	 * Make sure this is called after wp-settings.php is loaded, or the `rest_get_server()` will throw 500's
	 *
	 * @return array
	 */
	static function get_all_rest_routes() {
		$wp_rest_server     = rest_get_server();
		return array_keys( $wp_rest_server->get_routes() );
	}


	/**
	 * Make sure this is called after wp-settings.php is loaded, or the `rest_get_server()` will throw 500's
	 *
	 * @return string[]
	 */
	static function get_all_rest_namespaces() {
		$wp_rest_server     = rest_get_server();
		return $wp_rest_server->get_namespaces();
	}


	/**
	 * Make sure this is called after wp-settings.php is loaded, or the `self::get_all_rest_routes()` will throw 500's
	 *
	 * @param $allowed_routes
	 *
	 * @return array
	 */
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


	/**
	 * Make sure this is called after wp-settings.php is loaded, or the `self::get_all_rest_routes()` will throw 500's
	 *
	 * @param bool $default_value
	 *
	 * @return array
	 */
	static function build_routes_rule_for_all( $default_value = true ) {
		// The full list of all routes in the system
		$all_routes = self::get_all_rest_routes();

		// Initialize our new rules
		$new_rules = array();

		// Loop through ALL routes, set all to the desired value
		foreach ( $all_routes as $route ) {
			$new_rules[$route] = $default_value;
		}

		// Return full list of all known routes with values defined
		return $new_rules;
	}


	/**
	 * Confirms if the passed value is either 'none' or another role defined in the system
	 *
	 * @param $role
	 *
	 * @return bool
	 */
	static function is_valid_role( $role ) {

		// If we requested 'none', we know it's okay
		if ( 'none' == $role ) {
			return true;
		}

		// Get all roles from the system. Loop through and see if one of them is the one we're asking about
		$editable_roles = get_editable_roles();
		foreach ( $editable_roles as $editable_role => $details ) {
			if ( $role == $editable_role ) {
				return true;
			}
		}

		// If we got here, we're trying to ask for an invalid user role
		return false;
	}


}
