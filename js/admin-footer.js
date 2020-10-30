jQuery( function() {

    jQuery('#dra-role').change( function() {
        window.location.href = window.location.origin + window.location.pathname + '?page=disable_rest_api_settings&role=' + jQuery(this).val();
    });

});
