jQuery(document).ready(function($) {

  "use strict";

  $('.nav-search').click(function(e) {
    e.preventDefault();
    $('.nav-search').hide();
    $('#searchform').show();
  });
});
