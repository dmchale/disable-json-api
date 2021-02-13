function maybe_show_dra_routes() {
    let manage = jQuery('input[name=default_allow]:checked').val().toString();
    if ( '0' === manage ) {
        jQuery('div#route-container, input#dra-reset-button').css( 'display', 'block' );
    } else {
        jQuery('div#route-container, input#dra-reset-button').css( 'display', 'none' );
    }
}

jQuery( function() {

    maybe_show_dra_routes();

    jQuery('select#dra-role').change( function() {
        window.location.href = window.location.origin + window.location.pathname + '?page=disable_rest_api_settings&role=' + jQuery(this).val();
    });

    jQuery('input[name=default_allow]').change( function() {
        maybe_show_dra_routes();
    });

});
