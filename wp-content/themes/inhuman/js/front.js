jQuery(document).ready(function($) {

  "use strict";

  $(".top-posts .arrow-left").click(function() {
    var cur = $(".top-posts-viewport").scrollLeft()
    var width = $(".top-posts-viewport").width()
    var new_pos = Math.max(cur - width, 0);
    $(".top-posts-viewport").animate({ scrollLeft: new_pos }, 500);
  });
  $(".top-posts .arrow-right").click(function() {
    var cur = $(".top-posts-viewport").scrollLeft()
    var width = $(".top-posts-viewport").width()
    var max = $(".top-posts-viewport")[0].scrollWidth;
    var new_pos = Math.min(cur + width, max);
    $(".top-posts-viewport").animate({ scrollLeft: new_pos }, 500);
  });
  
  let handleShowSpam = function(e) {
      e.preventDefault();
      $(this).parent().hide().next().show().removeClass("hide");
  }
  $(".spam-shield a").click(handleShowSpam);

  $("#verify-submit").click(function(e) {
    e.preventDefault();
  	var data = {
		  action: 'inhuman_user_verify_token',
      token: $("#verify-token").val()
	  };
    $.ajax({
      type: 'POST',
      url: $('#php_data_ajax_url').val() + "?action=inhuman_user_verify_token",
      data: JSON.stringify(data),
      contentType: "application/json"
    })
    .done(function(res) {
      if (res.success) {
        alert("Email verified!");
        window.location = "/";
      } else {
        alert("Email verification failed: " + res.error);
      }
    })
    .fail(function(err) {
      console.log("Error verifying token: " + JSON.stringify(err));
    });
  });
  if ("/verify/" === window.location.pathname) {
    var params = new URLSearchParams(document.location.search.substring(1));
    var token = params.get("token");
    $("#verify-token").val(token);
    $("#verify-submit").trigger("click");
  }

});
