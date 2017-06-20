jQuery(document).ready(function($) {

  "use strict";

  var isotope_opts = {
    itemSelector: '.card',
    layoutMode: 'masonry',
    masonry: {
      columnWidth: '.grid-sizer',
      gutter: '.gutter-sizer',
      percentPosition: true
    }
  };
  $('.featured-posts').isotope(isotope_opts);
  $('.all-posts').isotope(isotope_opts);

  let form = $('#add-screenshot-form');
  $(form).submit(function(event) {
    event.preventDefault();

    let formData = $(form).serializeForm();
    formData.action = 'inhuman_add_screenshot';
    formData.nonce = php_data.nonce;

    $.ajax({
      type: 'POST',
      url: php_data.ajax_url,
      data: formData
    })
      .done(function(res) {
        alert(res);
      })
      .fail(function(err) {
      });
  });

  $('#sidebar-toggle').sidr({
    name: 'sidebar-menu',
    side: 'right'
  });

  var next_move = "expand";
  $("#sidebar-toggle").click(function() {
    console.log(next_move);
    var css = {};
    if (next_move == "expand"){
      css = {
        marginRight: '260px', // Equals your Sidr width
      };
      next_move = "shrink";
    } else {
      css = {
        marginRight: '0px', // Return to original position
      };
      next_move = "expand";
    }
    $(this).animate(css, 175, "linear"); // Timed to default Sidr movement
  });
  
  // Footer corner menu behavior
  $('.footer-button').click(function(e) {
    $('.footer-menu').toggleClass('visible');
  });

  $('.load-more-button').append( '<span class="load-more">Click here to load earlier stories</span>' );
  var button = $('.all-posts .load-more');
  var page = 2;
  var loading = false;

  $('body').on('click', '.load-more', function(){
	  if( ! loading ) {
		  loading = true;
		  var data = {
			  action: 'ajax_load_more',
			  page: page,
			  query: php_data.posts_query,
		  };
		  $.post(php_data.ajax_url, data, function(res) {
			  if( res.success) {
          var $html = $(res.data)
				  $('.all-posts').append($html);
				  $('.all-posts').isotope('appended', $html);
				  $('.all-posts').append(button);
				  page = page + 1;
				  loading = false;
			  } else {
				  console.log(res);
			  }
		  }).fail(function(xhr, textStatus, e) {
			  console.log(xhr.responseText);
		  });
	  }
  });
});
