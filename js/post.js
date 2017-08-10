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

    var postId = $("#post_id").val();
    if (localStorage["liked-"+postId])
      return;
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
         localStorage["liked-"+postId] = true;
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

  // generates a v4 UUID
  function makeUuid() { // eslint-disable-line no-unused-vars
    // get sixteen unsigned 8 bit random values
    var randomValues = window
      .crypto
      .getRandomValues(new Uint8Array(36));

    return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
      var i = Array.prototype.slice.call(arguments).slice(-2)[0]; // grab the `offset` parameter
      var r = randomValues[i] % 16|0, v = c === 'x' ? r : (r & 0x3 | 0x8);
      return v.toString(16);
    });
  }

  function callExtension(message, onSuccess, onFailure) {
    var handlers = {
      id: makeUuid(),
      onSuccess: function(e) {
        this.removeListeners();
        onSuccess();
      },
      onFailure: function(e) {
        this.removeListeners();
        onFailure();
      },
      addListeners: function() {
        document.addEventListener(this.id + '-success', this.onSuccess.bind(this));
        document.addEventListener(this.id + '-failure', this.onFailure.bind(this));
      },
      removeListeners: function() {
        document.removeEventListener(this.id + '-success', this.onSuccess);
        document.removeEventListener(this.id + '-failure', this.onFailure);
      }
    };
    handlers.addListeners();
    document.dispatchEvent(new CustomEvent(message, {detail: handlers.id}));
  }

  $("#delete-screenshot").click(function(e) {
    e.preventDefault();
    callExtension("inhumanRequestLogin", function() {
  	  var data = {
		    action: 'inhuman_delete_screenshot',
        post_id: $("#post_id").val()
	    };
      $.ajax({
        type: 'POST',
        url: php_data.ajax_url + "?action=inhuman_delete_screenshot",
        data: JSON.stringify(data),
        contentType: "application/json"
      })
       .done(function(res) {
         alert("Screenshot deleted.");
         window.location = "/";
       })
       .fail(function(err) {
         console.log("Error deleting screenshot: " + JSON.stringify(err));
       });
    }, function() {
      console.log("Login failure");
    });
  });

});
