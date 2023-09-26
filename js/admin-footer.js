function dra_set_route_display( new_display ) {
    document.querySelectorAll('div#route-container, input#dra-reset-button').forEach( el => {
        el.addEventListener( 'click', () => {
            el.style.display = new_display;
        });
    });
}

function dra_maybe_show_routes() {
    let manage = document.querySelector('input[name=default_allow]:checked').value.toString();
    if ( '0' === manage ) {
        dra_set_route_display( 'block' );
    } else {
        dra_set_route_display( 'none' );
    }
}

document.addEventListener( 'DOMContentLoaded', () => {

    dra_maybe_show_routes();

    document.getElementById('dra-role').addEventListener( 'change', function() {
        window.location.href = window.location.origin + window.location.pathname + '?page=disable_rest_api_settings&role=' + this.value;
    });

    document.querySelectorAll('input[name=default_allow]').forEach( el => {
        el.addEventListener( 'change', () => {
            dra_maybe_show_routes();
        });
    });

});
