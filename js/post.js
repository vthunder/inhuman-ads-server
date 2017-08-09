jQuery(document).ready(function($) {

  "use strict";

  $(".screenshot-actions .like").hover(function(e) {
    e.preventDefault();
    $(".like-box-wrapper").toggle();
  });
  $(".screenshot-actions .share").hover(function(e) {
    e.preventDefault();
    $(".share-box-wrapper").toggle();
  });

  $(".like-emoji-link").click(function(e) {
    e.preventDefault();

//    $(".like-box-wrapper").toggle();

	  var data = {
		  action: 'inhuman_like',
		  post_id: $("#post_id").val(),
		  emoji: this.getAttribute('data-emoji')
	  };

    $.ajax({
      type: 'POST',
      url: php_data.ajax_url + "?action=inhuman_like",
      data: JSON.stringify(data)
    })
      .done(function(res) {
        res = JSON.parse(res);
		    if(res.success) {
          var count_span = $(".like-emoji-count-box .count-text");
          var count = parseInt(count_span.text());
          count_span.text(count + 1);
		    } else {
          console.log("Error liking screenshot:");
			    console.log(res);
		    }
      })
      .fail(function(err) {
        console.log("Error liking screenshot:");
        console.log(err.responseText);
      });
  });

  $("#user-setup .button").click(function(e) {
    e.preventDefault();

  	  var data = {
		  action: 'inhuman_user_setup',
		  name: $('#user-setup input[name="name"]').val(),
		  email: $('#user-setup input[name="email"]').val()
	  };

    $.ajax({
      type: 'POST',
      url: php_data.ajax_url + "?action=inhuman_user_setup",
      data: JSON.stringify(data)
    })
      .done(function(res) {
        res = JSON.parse(res);
		    if(res.success) {
          window.location.reload();
		    } else {
          if (res.error == "Name taken") {
            $("#name-error").text("This name is taken.");
          } else {
            console.log("Error setting user details:");
			      console.log(res);
          }
		    }
      })
      .fail(function(err) {
        console.log("Error setting user details:");
        console.log(err.responseText);
      });
  });

});
