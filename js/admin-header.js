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
        jQuery(this).attr('aria-expanded', 'true');
        answer.css({"height": scrollHeight}).attr('aria-hidden', 'false').focus();
        answer.find('input').prop('disabled', false);
      } else {
        accordion.removeClass("expanded");
        answer.css({"height": "0"}).attr('aria-hidden', 'true');
        answer.find('input').prop('disabled', true);
        jQuery(this).attr('aria-expanded', 'false');
      }
    });
  });
          