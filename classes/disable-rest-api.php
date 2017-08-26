<?php

/**
 * Disable_REST_API class
 *
 * Most of the work is done in here
 */
class Disable_REST_API {

	private $is_loaded = false;
	private $base_file_path;    // stores 'disable-json-api/disable-json-api.php' typically

	/**
	 * Disable_REST_API constructor.
	 *
	 * @param $path
	 */
	public function __construct( $path ) {

		$this->initialize_variables( $path );

		if ( is_admin() ) {
			add_action( 'admin_menu', array( &$this, 'define_admin_link' ) );
		}

		add_filter( 'rest_authentication_errors', array( &$this, 'whitelist_routes' ), 1 );

	}


	/**
	 * Checks for a current route being requested, and processes the whitelist
	 */
	public function whitelist_routes() {

		$currentRoute = $GLOBALS['wp']->query_vars['rest_route'];
		if ( ! empty( $currentRoute ) && ! $this->is_whitelisted( $currentRoute ) ) {
			add_filter( 'rest_authentication_errors', array( &$this, 'only_allow_logged_in_rest_access' ), 99 );
		}

	}


	/**
	 * Checks a route for whether it belongs to the whitelist
	 *
	 * @param $currentRoute
	 *
	 * @return mixed
	 */
	private function is_whitelisted( $currentRoute ) {

		if ( get_option( 'DRA_route_whitelist' ) ) {
			return array_reduce( get_option( 'DRA_route_whitelist' ), function ( $isMatched, $pattern ) use ( $currentRoute ) {
				return $isMatched || preg_match( '@^' . htmlspecialchars_decode( $pattern ) . '$@i', $currentRoute );
			}, false );
		}

		return false;

	}


	/**
	 * Sets default values for all class variables
	 *
	 * @param $path
	 *
	 * @return boolean
	 */
	private function initialize_variables( $path ) {

		// Only should be run once per page load
		if ( $this->is_loaded ) {
			return false;
		}
		$this->is_loaded = true;

		// Set variable so the class knows how to reference the plugin
		$this->base_file_path = plugin_basename( $path );

		return true;

	}


	/**
	 * Returning an authentication error if a user who is not logged in tries to query the REST API
	 *
	 * @param $access
	 *
	 * @return WP_Error
	 */
	public function only_allow_logged_in_rest_access( $access ) {

		if ( ! is_user_logged_in() ) {
			return new WP_Error( 'rest_cannot_access', __( 'DRA: Only authenticated users can access the REST API.', 'disable-json-api' ), array( 'status' => rest_authorization_required_code() ) );
		}

		return $access;

	}


	/**
	 * Add a menu
	 */
	public function define_admin_link() {

		add_options_page( 'Disable REST API Settings', 'Disable REST API', 'manage_options', 'disable_rest_api_settings', array(
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
	 * @return mixed
	 */
	public function settings_link( $links ) {

		$settings_url = admin_url() . "options-general.php?page=disable_rest_api_settings";
		$settings_link = "<a href='$settings_url'>Settings</a>";
		array_unshift( $links, $settings_link );

		return $links;
	}


	/**
	 * Menu Callback
	 */
	public function settings_page() {

		$this->maybe_process_settings_form();

		// Render the settings template
		include( dirname( __FILE__ ) . "/../admin.php" );

	}


	/**
	 * Process the admin page settings form submission
	 */
	private function maybe_process_settings_form() {

		if ( ! ( isset( $_POST['_wpnonce'] ) && check_admin_referer( 'DRA_admin_nonce' ) ) ) {
			return;
		}

		// If resetting, clear the option and exit the function
		if ( isset( $_POST['reset'] ) ) {
			update_option( 'DRA_route_whitelist', '' );
			return;
		}

		// Catch the routes that should be whitelisted, and save them to the Options table
		$rest_routes = ( isset( $_POST['rest_routes'] ) )
			? wp_unslash( array_map( 'htmlspecialchars', $_POST['rest_routes'] ) )
			: '';
		update_option( 'DRA_route_whitelist', $rest_routes );

	}


}
