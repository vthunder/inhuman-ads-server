jQuery(document).ready(function($) {

  "use strict";

  $("#screenshot-meta-form").submit(function(e) {
    e.preventDefault();

    var data = { action: "inhuman_update_screenshot" };
    $.each(this.elements, function(i, v){
      var input = $(v);
      if ("checkbox" == input.prop('type'))
        data[input.attr("name")] = input.prop('checked');
      else        
        data[input.attr("name")] = input.val();
      delete data["undefined"];
    });

    $.post($('#php_data_ajax_url').val(), data, function(res) {
      if(res.success) {
        location = location.protocol + '//' + location.host +
          "?nocache=" + (new Date()).getTime();
			} else {
        alert("Error updating shot. Try again later!");
			}
    }, 'json').fail(function(xhr, textStatus, e) {
			console.log(xhr.responseText);
		});
  });

  //
  // Offensive content "load anyway" link
  //
  let handleShowSpam = function(e) {
      e.preventDefault();
      $(this).parent().hide().next().show().removeClass("hide");
  }
  $(".spam-shield a").click(handleShowSpam);

  // Style liked emoji differently
  var postId = $("#post_id").val();
  if (localStorage["liked-"+postId]) {
    $('.like-emoji-link[data-emoji="' + localStorage["liked-"+postId] + '"]').addClass("liked");
  }
  
  //
  // Wire up "like" buttons
  //
  $(".like-emoji-link").click(function(e) {
    e.preventDefault();

    if (localStorage["liked-"+postId])
      return;

	  var data = {
		  action: 'inhuman_like',
		  post_id: $("#post_id").val(),
		  emoji: this.getAttribute('data-emoji')
	  };

    $.ajax({
      type: 'POST',
      url: $('#php_data_ajax_url').val() + "?action=inhuman_like",
      data: JSON.stringify(data)
    })
     .done(function(res) {
       res = JSON.parse(res);
		   if(res.success) {
         var count_span = $(".like .count-text."+data.emoji);
         var count = parseInt(count_span.text());
         count_span.text(count + 1);
         localStorage["liked-"+postId] = data.emoji;
         $('.like-emoji-link[data-emoji="' + data.emoji + '"]').addClass("liked");
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

  //
  // Wire up "share" buttons
  //
  let bumpHighScore = function(shareFn) {
    var postId = $("#post_id").val();
    if (localStorage["shared-"+postId])
      return shareFn();
    $(".share-box-wrapper").hide();

	  var data = {
		  action: 'inhuman_share',
		  post_id: $("#post_id").val()
	  };

    $.ajax({
      type: 'POST',
      url: $('#php_data_ajax_url').val() + "?action=inhuman_share",
      data: JSON.stringify(data)
    })
     .done(function(res) {
       res = JSON.parse(res);
		   if(res.success) {
         localStorage["shared-"+postId] = true;
		   } else {
         console.log("Error sharing screenshot:");
			   console.log(res);
		   }
       shareFn();
     })
     .fail(function(err) {
       console.log("Error liking screenshot:");
       console.log(err.responseText);
       shareFn();
     });
  };

  $(".twitter-share-button").click(function(e) {
    e.preventDefault();
    var status = this.getAttribute('data-twitter-status');
    bumpHighScore(function(e) {
      document.location = "http://twitter.com/intent/tweet?status=" + status;
    });
  });
  $(".facebook-share-button").click(function(e) {
    e.preventDefault();
    var u = this.getAttribute('data-fb-u');
    var title = this.getAttribute('data-fb-title');
    bumpHighScore(function(e) {
      document.location = "http://www.facebook.com/sharer/sharer.php?u=" + u + "&title=" + title;
    });
  });
  $(".email-share-button").click(function(e) {
    e.preventDefault();
    var email = this.getAttribute('data-email');
    bumpHighScore(function(e) {
      document.location = "mailto:?Subject=" + email;
    });
  });

  //
  // New user setup
  //
  $("#existing-account-link").click(function(e) {
    e.preventDefault();
    $('#user-setup-create').add('#existing-account-link').hide();
    $('#user-setup-existing').add('#create-account-link').show();
  });
  $("#create-account-link").click(function(e) {
    e.preventDefault();
    $('#user-setup-create').add('#existing-account-link').show();
    $('#user-setup-existing').add('#create-account-link').hide();
  });

  $("#user-setup-create .button").click(function(e) {
    e.preventDefault();

  	var data = {
		  action: 'inhuman_user_setup',
		  name: $('#user-setup-create input[name="name"]').val(),
		  email: $('#user-setup-create input[name="email"]').val()
	  };

    $.ajax({
      type: 'POST',
      url: $('#php_data_ajax_url').val() + "?action=inhuman_user_setup",
      data: JSON.stringify(data)
    })
     .done(function(res) {
       res = JSON.parse(res);
		   if(res.success) {
         window.location.reload();
		   } else {
         if (res.error == "Name taken") {
           $("#user-setup-error").text("This name is taken.");
         }
         else {
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

  $("#user-setup-existing .button").click(function(e) {
    e.preventDefault();

  	var data = {
		  action: 'inhuman_user_setup_existing',
		  email: $('#user-setup-existing input[name="email"]').val()
	  };

    $.ajax({
      type: 'POST',
      url: $('#php_data_ajax_url').val() + "?action=inhuman_user_setup_existing",
      data: JSON.stringify(data)
    })
     .done(function(res) {
       res = JSON.parse(res);
		   if(res.success) {
         $('#user-setup-existing').hide();
         $("#user-setup-error").text("Email sent! Remember to check your spam folder.");
		   } else {
         if (res.error) {
           $("#user-setup-error").text("Error: " + res.error);
         }
         else {
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

  	var data = {
		  action: 'inhuman_delete_screenshot',
      post_id: $("#post_id").val()
	  };

    $.post($('#php_data_ajax_url').val(), data, function(res) {
      if(res.success) {
        alert("Screenshot deleted.");
        location = location.protocol + '//' + location.host +
          "?nocache=" + (new Date()).getTime();
			} else {
        alert("Error deleting shot. Try again later!");
			}
    }, 'json').fail(function(xhr, textStatus, e) {
			console.log(xhr.responseText);
		});
  });

  $("#report-link").click(function(e) {
    e.preventDefault();
  	var data = {
		  action: 'inhuman_report',
      post_id: $("#post_id").val()
	  };
    $.ajax({
      type: 'POST',
      url: $('#php_data_ajax_url').val() + "?action=inhuman_report",
      data: JSON.stringify(data),
      contentType: "application/json"
    })
     .done(function(res) {
       alert("Post reported");
       window.location = "/";
     })
     .fail(function(err) {
       console.log("Error verifying token: " + JSON.stringify(err));
     });
  });

});
