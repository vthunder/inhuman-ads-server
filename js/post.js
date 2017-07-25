jQuery(document).ready(function($) {

  "use strict";

  $('#sidebar-toggle').sidr({
    name: 'sidebar-menu',
    side: 'left'
  });

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
          alert("error");
			    console.log(res);
		    }
      })
      .fail(function(err) {
        alert("error");
        alert(err.responseText);
      });
  });

});
