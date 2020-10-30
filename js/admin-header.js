function dra_namespace_click(namespace, id) {
    if (jQuery('#dra_namespace_' + id).is(":checked")) {
        jQuery("#DRA_container input[data-namespace='" + namespace + "']").prop('checked', true);
    } else {
        jQuery("#DRA_container input[data-namespace='" + namespace + "']").prop('checked', false);
    }
};
