function dra_namespace_click(namespace, id) {
    if (document.getElementById('#dra_namespace_' + id).checked) {
        document.getElementById("#route-container input[data-namespace='" + namespace + "']").checked = true;
    } else {
        document.getElementById("#route-container input[data-namespace='" + namespace + "']").checked = false;
    }
}