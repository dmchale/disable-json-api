function dra_namespace_click(namespace, id) {
    if (jQuery('#dra_namespace_' + id).is(":checked")) {
        jQuery("#route-container input[data-namespace='" + namespace + "']").prop('checked', true);
    } else {
        jQuery("#route-container input[data-namespace='" + namespace + "']").prop('checked', false);
    }
}

jQuery(function () {
    jQuery('.accordion-expand').click(function () {
      var accordion = jQuery(this).parents('.accordion-container');
      var answer = accordion.find('ul');
      var scrollHeight = answer.prop("scrollHeight");
  
      if(!accordion.hasClass("expanded")) {
        accordion.addClass("expanded");
        answer.css({"height": scrollHeight});
      } else {
        accordion.removeClass("expanded");
        answer.css({"height": "0"});
      }
    });
  });
          