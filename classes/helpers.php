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
			$new_rules[esc_html($route)] = $new_value;
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
			$new_rules[esc_html($route)] = $default_value;
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


	/**
	 * Check the WP Option for our stored values of which routes should be allowed based on the supplied role
	 *
	 * @param $role
	 * @param bool $get_allowed
	 *
	 * @return array
	 */
	static function get_allowed_routes( $role, $get_allowed = true ) {
		$arr_option = get_option( 'disable_rest_api_options', array() );

		// If we have an empty array, just return that
		if ( empty( $arr_option ) ) {
			return $arr_option;
		}

		$option_rules = array();
		$allowed_rules = array();

		if ( 'none' == $role && ! isset( $arr_option['roles']['none'] ) ) {

			// This helps us bridge the gap from plugin version <=1.5.1 to >=1.6.
			// We didn't use to store results based on role, but we want to return the values for "unauthenticated users" if we have recently upgraded
			$option_rules = ( array ) DRA_Helpers::build_routes_rule( $arr_option );

		} elseif ( isset( $arr_option['roles'][$role]['allow_list'] ) ) {

			// If we have a definition for the currently requested role, return it
			$option_rules = ( array ) $arr_option['roles'][$role]['allow_list'];

		} else {

			// If we failed all the way down to here, return a default array since we're asking for a role we don't have a definition for yet
			$option_rules = ( array ) DRA_Helpers::build_routes_rule_for_all( true );

		}

		// Loop through and only save the keys that have a value pairing of true
		foreach ( $option_rules as $key => $value ) {
			if ( $get_allowed === $value ) {
				$allowed_rules[] = $key;
			}
		}

		// Get rid of &lt; and &gt; before doing our comparisons
		$allowed_rules = array_map( 'htmlspecialchars_decode', $allowed_rules );

		// Return our array of allowed rules
		return $allowed_rules;

	}


	/**
	 * Return the setting for what the default route behavior is for a specified role
	 *
	 * @param $role
	 *
	 * @return bool
	 */
	static function get_default_allow_for_role( $role ) {
		$arr_option = get_option( 'disable_rest_api_options', array() );

		// If we have an empty array, return false so we deny access
		if ( empty( $arr_option ) ) {
			return false;
		}

		// Unauthorized users default to DONT ALLOW, authorized users default to DO ALLOW
		$default_allow = ( 'none' == $role ) ? false : true;

		if ( isset( $arr_option['roles'][$role]['default_allow'] ) ) {
			$default_allow = $arr_option['roles'][$role]['default_allow'];
		}

		// Return our default rule
		return ( bool ) $default_allow;

	}


	/**
	 * Returns the translated name of the role based on provided role slug
	 *
	 * @param $role
	 *
	 * @return string
	 */
	static function get_role_name( $role ) {

		if ( 'none' == $role ) {
			return __( 'Unauthenticated', 'disable-json-api' );
		}

		$editable_roles = get_editable_roles();
		if ( isset( $editable_roles[$role] ) ) {
			return translate_user_role( $editable_roles[$role]['name'] );
		}

		return '';

	}

}
