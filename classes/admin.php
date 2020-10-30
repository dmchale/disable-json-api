<?php
class DRA_Admin {

	/**
	 * Loop through all routes returned by the REST API and display them on-screen
	 *
	 * @param string $role
	 */
	static function display_route_checkboxes( $role = 'none' ) {
		$all_namespaces     = DRA_Helpers::get_all_rest_namespaces();
		$all_routes         = DRA_Helpers::get_all_rest_routes();
		$allowed_routes     = DRA_Helpers::get_allowed_routes( $role );

//	    var_dump($allowed_routes);

		$loopCounter       = 0;
		$current_namespace = '';

		foreach ( $all_routes as $route ) {
			$is_route_namespace = in_array( ltrim( $route, "/" ), $all_namespaces );
			$checkedProp        = self::get_route_checked_prop( $route, $allowed_routes );

			if ( $is_route_namespace || "/" == $route ) {
				$current_namespace = $route;
				if ( 0 != $loopCounter ) {
					echo "</ul>";
				}

				$route_for_display = ( "/" == $route ) ? "/ <em>" . esc_html__( "REST API ROOT", "disable-json-api" ) . "</em>" : esc_html( $route );
				echo "<label class='switch'><input name='rest_routes[]' value='$route' type='checkbox' id='dra_namespace_$loopCounter' onclick='dra_namespace_click(\"$route\", $loopCounter)' $checkedProp><span class='slider'></span></label><h2>&nbsp;$route_for_display</h2><ul>";

				if ( "/" == $route ) {
					echo "<li>" . sprintf( esc_html__( "On this website, the REST API root is %s", "disable-json-api" ), "<strong>" . rest_url() . "</strong>" ) . "</li>";
				}

			} else {
				echo "<li><label class='switch'><input name='rest_routes[]' value='$route' type='checkbox' data-namespace='$current_namespace' $checkedProp><span class='slider'></span></label>&nbsp;" . esc_html( $route ) . "</li>";
			}

			$loopCounter ++;
		}
		echo "</ul>";
	}


	/**
	 * During comparison, encode the route being requested in the same fashion that it's stored in the database option
	 * Encoding during save happens in Disable_REST_API::maybe_process_settings_form()
	 *
	 * @param $route
	 * @param $allowed_routes
	 *
	 * @return string
	 */
	static function get_route_checked_prop( $route, $allowed_routes ) {
		$is_route_checked = in_array( esc_html( $route ), array_map( 'esc_html', $allowed_routes ), true );

		return checked( $is_route_checked, true, false );
	}

}
