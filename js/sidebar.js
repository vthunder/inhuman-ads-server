jQuery(document).ready(function($) {

  "use strict";

  $('#sidebar-toggle').add('#sidebar .close-button').sidr({
    name: 'sidebar',
    side: 'left',
    onOpen: function() {
      $('#sidebar-toggle').dimBackground();
    },
    onClose: function() {
      $.undim();
    }
  });
});
