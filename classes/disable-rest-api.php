<?php

/**
 * Disable_REST_API class
 *
 * Most of the work is done in here
 */
class Disable_REST_API {

	const MENU_SLUG = 'disable_rest_api_settings';
	const CAPABILITY = 'manage_options';

	/**
	 * Stores 'disable-json-api/disable-json-api.php' typically
	 *
	 * @var string
	 */
	private $base_file_path;

	/**
	 * Disable_REST_API constructor.
	 *
	 * @param $path
	 */
	public function __construct( $path ) {

		// Set variable so the class knows how to reference the plugin
		$this->base_file_path = plugin_basename( $path );

		add_action( 'admin_menu', array( &$this, 'define_admin_link' ) );

		add_filter( 'rest_authentication_errors', array( &$this, 'whitelist_routes' ), 1 );

	}


	/**
	 * Checks for a current route being requested, and processes the whitelist
	 *
	 * @return void
	 */
	public function whitelist_routes() {

		$currentRoute = $this->get_current_route();
		if ( ! empty( $currentRoute ) && ! $this->is_whitelisted( $currentRoute ) ) {
			add_filter( 'rest_authentication_errors', array( &$this, 'only_allow_logged_in_rest_access' ), 99 );
		}

	}

	/**
	 * Current REST route getter.
	 *
	 * @return string
	 */
	private function get_current_route()
	{
		return empty($GLOBALS['wp']->query_vars['rest_route']) ?
			'' :
			untrailingslashit($GLOBALS['wp']->query_vars['rest_route']);
	}


	/**
	 * Checks a route for whether it belongs to the whitelist
	 *
	 * @param $currentRoute
	 *
	 * @return boolean
	 */
	private function is_whitelisted( $currentRoute ) {

		return array_reduce( $this->get_route_whitelist_option(), function ( $isMatched, $pattern ) use ( $currentRoute ) {
			return $isMatched || (bool) preg_match( '@^' . htmlspecialchars_decode( $pattern ) . '$@i', $currentRoute );
		}, false );

	}

	/**
	 * Get `DRA_route_whitelist` option array from database
	 *
	 * @return array
	 */
	private function get_route_whitelist_option() {

		return (array) get_option( 'DRA_route_whitelist', array() );

	}

	/**
	 * Returning an authentication error if a user who is not logged in tries to query the REST API
	 *
	 * @param $access
	 *
	 * @return WP_Error|null|boolean
	 */
	public function only_allow_logged_in_rest_access( $access ) {

		if ( ! is_user_logged_in() ) {
			return new WP_Error( 'rest_cannot_access', __( 'DRA: Only authenticated users can access the REST API.', 'disable-json-api' ), array( 'status' => rest_authorization_required_code() ) );
		}

		return $access;

	}


	/**
	 * Add a menu
	 *
	 * @return void
	 */
	public function define_admin_link() {

		add_options_page( 'Disable REST API Settings', 'Disable REST API', self::CAPABILITY, self::MENU_SLUG, array(
			&$this,
			'settings_page'
		) );
		add_filter( "plugin_action_links_$this->base_file_path", array( &$this, 'settings_link' ) );

	}


	/**
	 * Add Settings Link to plugins page
	 *
	 * @param $links
	 *
	 * @return array
	 */
	public function settings_link( $links ) {

		$settings_url = menu_page_url(self::MENU_SLUG);
		$settings_link = "<a href='$settings_url'>Settings</a>";
		array_unshift( $links, $settings_link );

		return $links;
	}


	/**
	 * Menu Callback
	 *
	 * @return void
	 */
	public function settings_page() {

		$this->maybe_process_settings_form();

		// Render the settings template
		include( __DIR__ . "/../admin.php" );

	}


	/**
	 * Process the admin page settings form submission
	 *
	 * @return void
	 */
	private function maybe_process_settings_form() {

		if ( ! ( isset( $_POST['_wpnonce'] ) && check_admin_referer( 'DRA_admin_nonce' ) ) ) {
			return;
		}

		if ( ! current_user_can( self::CAPABILITY ) ) {
			return;
		}

		// If resetting, clear the option and exit the function
		if ( isset( $_POST['reset'] ) ) {
			delete_option( 'DRA_route_whitelist' );
			return;
		}

		// Catch the routes that should be whitelisted, and save them to the Options table
		$rest_routes = ( isset( $_POST['rest_routes'] ) )
			? wp_unslash( array_map( 'htmlspecialchars', $_POST['rest_routes'] ) )
			: null;

		if ( empty($rest_routes) ) {
			delete_option( 'DRA_route_whitelist' );
		} else {
			update_option( 'DRA_route_whitelist', $rest_routes );
		}

	}


}
