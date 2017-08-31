<?php
$DRA_route_whitelist = get_option( 'DRA_route_whitelist' );
?>

    <style>
        #DRA_target ul li {
            padding-left: 20px;
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

        jQuery(function ($) {

            var lastNamespace = '';
            var formOutput = '';
            var DRA_route_whitelist = <?php echo ( $DRA_route_whitelist ) ? json_encode( $DRA_route_whitelist ) : "[]"; ?>;

            //console.log(DRA_route_whitelist);

            var restPath = '<?php echo DRA_get_rest_api_path(); ?>';
            $.getJSON(restPath, '', function (data) {

                var routes = data["routes"];        // get all routes from JSON

                /*
                 * Loop through all detected routes
                 */
                var loopCounter = 0;
                $.each(routes, function (key, val) {
                    var route = key;    // individual route, used as checkbox values
                    var routeDisplay = route.replace(/</gi, "&lt;").replace(/>/gi, "&gt;");     // HTML-encode lt & gt tags for display on page
                    $.each(val, function (key2, curNamespace) {
                        //console.log(route + " ... " + DRA_route_whitelist.indexOf(routeDisplay));
                        var checkedProp = ( -1 != DRA_route_whitelist.indexOf(routeDisplay) ) ? " checked='checked' " : "";
                        if ('namespace' == key2 && '' != curNamespace) {    // Ignore top-level endpoint(s) by excluding empty strings
                            if ('' == lastNamespace || curNamespace != lastNamespace) {
                                if ('' != lastNamespace)
                                    formOutput += "</ul>";
                                formOutput += "<h2><label><input name='rest_routes[]' value='" + route + "' type='checkbox' id='dra_namespace_" + loopCounter + "' onclick='dra_namespace_click(\"" + curNamespace + "\", " + loopCounter + ")' " + checkedProp + ">&nbsp;" + routeDisplay + "</label></h2><ul>";
                            } else {
                                formOutput += "<li><label><input name='rest_routes[]' value='" + route + "' type='checkbox' data-namespace='" + curNamespace + "' " + checkedProp + ">&nbsp;" + routeDisplay + "</label></li>";
                            }
                            lastNamespace = curNamespace;
                        }
                    });
                    loopCounter++;
                });

                formOutput += "</ul>";

                $('#DRA_target').html(formOutput);

            });

        });
    </script>

    <div class="wrap">
        <h1><?php echo esc_html__( "Disable REST API", "disable-json-api" ); ?></h1>
        <p><?php echo esc_html__( "By default, this plugin ensures that the entire REST API is protected from non-authenticated users. You may use this page to specify which endpoints should be allowed to behave as normal.", "disable-json-api" ); ?></p>
        <p>
            <strong><?php echo esc_html__( "IMPORTANT NOTE:", "disable-json-api" ); ?></strong> <?php echo esc_html__( "Checking a box merely restores default functionality to an endpoint . Other authentication and/or permissions may still be required for access, or other themes / plugins may also affect access to those endpoints . ", "disable - json - api" ); ?>
        </p>

        <form method="post" action="" id="DRA_form">
			<?php wp_nonce_field( 'DRA_admin_nonce' ); ?>

            <div id="DRA_target"></div>

			<?php submit_button(); ?>
            <input type="submit" name="reset"
                   value="<?php echo esc_attr__( "Reset Whitelisted Routes", "disable-json-api" ); ?>"
                   onclick="return confirm('<?php echo esc_attr__( "Are you sure you wish to clear all whitelisted rules?", "disable-json-api" ); ?>');">
        </form>
    </div>

<?php
/**
 * The REST API lives at a different path when pretty permalinks are not in use
 *
 * /wp-json lives at the root of the website so we use home_url()
 * /?rest_route=/ lives at the root of the wordpress install so we use site_url()
 */
function DRA_get_rest_api_path() {
	$restPath = home_url() . "/wp-json/";
	$permalink_structure = get_option( 'permalink_structure' );
	if ( empty( $permalink_structure ) ) {
		$restPath = site_url() . "/?rest_route=/";
	}

	return $restPath;
}
