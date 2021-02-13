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
				echo "<label class='switch'><input name='rest_routes[]' value='$route' type='checkbox' id='dra_namespace_$loopCounter' onclick='dra_namespace_click(\"$route\", $loopCounter)' $checkedProp><span class='slider'></span></label><h2><label for='dra_namespace_$loopCounter'>&nbsp;$route_for_display</label></h2><ul>";

				if ( "/" == $route ) {
					echo "<li>" . sprintf( esc_html__( "On this website, the REST API root is %s", "disable-json-api" ), "<strong>" . rest_url() . "</strong>" ) . "</li>";
				}

			} else {
				echo "<li><label class='switch'><input name='rest_routes[]' id='dra_namespace_$loopCounter' value='$route' type='checkbox' data-namespace='$current_namespace' $checkedProp><span class='slider'></span></label><label for='dra_namespace_$loopCounter'>&nbsp;" . esc_html( $route ) . "</label></li>";
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


	/**
	 * Displays setting for default role on admin page
	 *
	 * @param $role
	 */
	static function display_role_default_allow( $role ) {
		$default_allow_true_checked = '';
		$default_allow_false_checked = '';

		$role_default_allow = DRA_Helpers::get_default_allow_for_role( $role );
		if ( $role_default_allow ) {
			$default_allow_true_checked = ' checked="checked"';
		} else {
			$default_allow_false_checked = ' checked="checked"';
		}

		/* translators: name of user role */
		echo sprintf( '<h2>%s</h2>', sprintf( esc_html__( 'Manage Rules for %s Users', 'disable-json-api' ), DRA_Helpers::get_role_name( $role ) ) );
        ?>
		<p style="font-style:italic;">
            <?php
            echo esc_html__( 'NOTE: New routes may be added in the future by plugins, themes, or WordPress itself.', 'disable-json-api' );
            echo '<br />';
            echo esc_html__( 'If you choose to manage access for a user role, you will have to come back and add permissions for any new routes later.', 'disable-json-api' );
            ?>
        </p>
		<label><input type="radio" name="default_allow" value="0" <?php echo $default_allow_false_checked; ?>>&nbsp;<?php echo esc_html__( 'Manage REST API Access', 'disable-json-api' ); ?></label>
		&nbsp;&nbsp;&nbsp;
		<label><input type="radio" name="default_allow" value="1" <?php echo $default_allow_true_checked; ?>>&nbsp;<?php echo esc_html__( 'Allow Full REST API Access', 'disable-json-api' ); ?></label>
		<?php
	}


}
