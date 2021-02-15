function dra_namespace_click(namespace, id) {
    if (jQuery('#dra_namespace_' + id).is(":checked")) {
        jQuery("#route-container input[data-namespace='" + namespace + "']").prop('checked', true);
    } else {
        jQuery("#route-container input[data-namespace='" + namespace + "']").prop('checked', false);
    }
}