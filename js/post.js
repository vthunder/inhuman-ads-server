jQuery(document).ready(function($) {

  "use strict";

  $('#sidebar-toggle').sidr({
    name: 'sidebar-menu',
    side: 'left'
  });

  var next_move = "expand";
  $("#sidebar-toggle").click(function() {
    var css = {};
    if (next_move == "expand"){
      css = {
        marginLeft: '260px', // Equals your Sidr width
      };
      next_move = "shrink";
    } else {
      css = {
        marginLeft: '0px', // Return to original position
      };
      next_move = "expand";
    }
    $(this).animate(css, 175, "linear"); // Timed to default Sidr movement
  });

  //
  // Hook up post edit/publish form
  //
});
