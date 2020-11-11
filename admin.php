<div class="wrap">
    <h1><?php echo esc_html__( "Disable REST API", "disable-json-api" ); ?></h1>
	<?php settings_errors( 'DRA-notices' ); ?>

    <p><?php echo esc_html__( "By default, this plugin ensures that the entire REST API is protected from non-authenticated users. You may use this page to specify which endpoints should be allowed to behave as normal.", "disable-json-api" ); ?></p>
    <p>
        <strong><?php echo esc_html__( "IMPORTANT NOTE:", "disable-json-api" ); ?></strong> <?php echo esc_html__( "Checking a box merely restores default functionality to an endpoint. Other authentication and/or permissions may still be required for access, or other themes/plugins may also affect access to those endpoints.", "disable-json-api" ); ?>
    </p>

    <hr />

    <div id="select-container">
        <?php echo esc_html__( "Rules for", "disable-json-api" ); ?>: <select name="role" id="dra-role">
            <option value="none"><?php echo esc_html__( "Unauthenticated Users", "disable-json-api" ); ?></option>
            <?php
            $role = ( isset( $_GET['role'] ) ) ? $_GET['role'] : 'none';
            wp_dropdown_roles( $role );
            ?>
        </select>
    </div>

    <hr />

    <form method="post" action="" id="DRA_form">
	    <?php wp_nonce_field( 'DRA_admin_nonce' ); ?>
        <input type="hidden" name="role" value="<?php echo esc_attr( $role ); ?>">

        <div id="default-allow-container">
            <?php DRA_Admin::display_role_default_allow( $role ); ?>
        </div>

        <hr />

        <div id="route-container">
            <?php DRA_Admin::display_route_checkboxes( $role ); ?>
        </div>

        <hr />

        <div id="button-container">
            <?php submit_button(); ?>
            <input type="submit" name="reset"
                   value="<?php echo esc_attr__( "Reset Allowed List of Routes", "disable-json-api" ); ?>"
                   onclick="return confirm('<?php echo esc_attr__( "Are you sure you wish to reset all allowed routes for this user role? This will set ALL routes to be enabled or disabled, depending on the default setting of this user role.", "disable-json-api" ); ?>');">
        </div>


    </form>

</div>
