<style>
    h2 { display: inline; }

    #DRA_container ul li {
        padding-left: 20px;
    }

    #DRA_container em {
        font-size: 0.8em;
    }

    .switch {
        position: relative;
        display: inline-block;
        width: 38px;
        height: 20px;
        margin-right: 0.4em;
    }

    .switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        -webkit-transition: .4s;
        transition: .4s;
        border-radius: 18px;
        display:inline;
    }

    .slider:before {
        position: absolute;
        content: "";
        height: 14px;
        width: 14px;
        left: 4px;
        bottom: 3px;
        background-color: #fff;
        -webkit-transition: .4s;
        transition: .4s;
        border-radius: 50%;
    }

    input:checked + .slider {
        background-color: #2196F3;
    }

    input:focus + .slider {
        box-shadow: 0 0 1px #2196F3;
    }

    input:checked + .slider:before {
        -webkit-transform: translateX(16px);
        -ms-transform: translateX(16px);
        transform: translateX(16px);
    }
</style>
<script>
    function dra_namespace_click(namespace, id) {
        if (jQuery('#dra_namespace_' + id).is(":checked")) {
            jQuery("input[data-namespace='" + namespace + "']").prop('checked', true);
        } else {
            jQuery("input[data-namespace='" + namespace + "']").prop('checked', false);
        }
    };
</script>

<div class="wrap">
    <h1><?php echo esc_html__( "Disable REST API", "disable-json-api" ); ?></h1>
	<?php settings_errors( 'DRA-notices' ); ?>
    <p><?php echo esc_html__( "By default, this plugin ensures that the entire REST API is protected from non-authenticated users. You may use this page to specify which endpoints should be allowed to behave as normal.", "disable-json-api" ); ?></p>
    <p>
        <strong><?php echo esc_html__( "IMPORTANT NOTE:", "disable-json-api" ); ?></strong> <?php echo esc_html__( "Checking a box merely restores default functionality to an endpoint. Other authentication and/or permissions may still be required for access, or other themes/plugins may also affect access to those endpoints.", "disable-json-api" ); ?>
    </p>

    <hr />

    <p>
    Rules for: <select name="role">
        <option value="none">Unauthenticated Users</option>
        <?php
        $role = ( isset( $_GET['role'] ) ) ? $_GET['role'] : 'none';
        wp_dropdown_roles( $role );
        ?>
    </select>
    </p>

    <hr />

    <form method="post" action="" id="DRA_form">
		<?php wp_nonce_field( 'DRA_admin_nonce' ); ?>
        <input type="hidden" name="role" value="<?php echo $role; ?>">

        <div id="DRA_container"><?php DRA_display_route_checkboxes( $role ); ?></div>

		<?php submit_button(); ?>
        <input type="submit" name="reset"
               value="<?php echo esc_attr__( "Reset Allowed List of Routes", "disable-json-api" ); ?>"
               onclick="return confirm('<?php echo esc_attr__( "Are you sure you wish to clear all allowed routes for this User Role?", "disable-json-api" ); ?>');">
    </form>
</div>

<script>
jQuery( function() {

    jQuery('select[name=role]').change( function() {
        let newVal = jQuery(this).val();
        let newUrl = window.location.origin + window.location.pathname + '?page=disable_rest_api_settings&role=' + newVal ;
        window.location.href = newUrl;
    });

});
</script>

<?php
/**
 * Loop through all routes returned by the REST API and display them on-screen
 *
 */
function DRA_display_route_checkboxes( $role = 'none' ) {
	$wp_rest_server     = rest_get_server();
	$all_namespaces     = $wp_rest_server->get_namespaces();
	$all_routes         = array_keys( $wp_rest_server->get_routes() );
	$allowed_routes = DRA_get_allowed_routes( $role );

	$loopCounter       = 0;
	$current_namespace = '';

	foreach ( $all_routes as $route ) {
		$is_route_namespace = in_array( ltrim( $route, "/" ), $all_namespaces );
		$checkedProp        = DRA_get_route_checked_prop( $route, $allowed_routes );

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
function DRA_get_route_checked_prop( $route, $allowed_routes ) {
	$is_route_checked = in_array( esc_html( $route ), $allowed_routes, true );

	return checked( $is_route_checked, true, false );
}


/**
 * Check the WP Option for our stored values of which routes should be allowed based on the supplied role
 *
 * @param $role
 *
 * @return array
 */
function DRA_get_allowed_routes( $role ) {
	$arr_option = is_array( get_option( 'DRA_route_whitelist' ) ) ? get_option( 'DRA_route_whitelist' ) : array();

	// If we have an empty array, just return that
	if ( empty( $arr_option ) ) {
	    return $arr_option;
    }

	// This helps us bridge the gap from plugin version <=1.5.1 to >=1.6.
    // We didn't use to store results based on role, but we want to return the values for "unauthenticated users" if we have recently upgraded
	if ( 'none' == $role && ! isset( $arr_option['roles']['none'] ) ) {
	    return $arr_option; // TODO: fix logic here, this isn't what we should be doing with the new option values
    }

	// If we have a definition for the currently requested role, return it
	if ( isset( $arr_option['roles'][ $role ] ) ) {
	    return ( array ) $arr_option['roles'][ $role ];
    }

    // If we failed all the way down to here, return an empty array since we're asking for a role we don't have a definition for yet
    return array();
}