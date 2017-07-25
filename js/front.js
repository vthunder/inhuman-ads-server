jQuery(document).ready(function($) {

  "use strict";

  $(window).scroll(function() {
    (window.pageYOffset || document.documentElement.scrollTop) > 200?
      $("header").addClass("smaller") :
      $("header").removeClass("smaller");
  });

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

    if (!php_data.user_display_name) {
      // user not logged in
      localStorage.setItem('addScreenshotAttempt', $('#add-screenshot-url').val());
      $('#a0LoginButton').click();
      return;
    }

    let formData = $(form).serializeForm();
    formData.action = 'inhuman_add_screenshot';
    formData.nonce = php_data.nonce;

    $.ajax({
      type: 'POST',
      url: php_data.ajax_url,
      data: formData
    })
      .done(function(res) {
        if ("0" === res) {
          alert("Couldn't submit screenshot, try again later");
        }
        location.reload();
      })
      .fail(function(err) {
        alert("Couldn't submit screenshot: " + err);
      });
  });

  var url = localStorage.getItem('addScreenshotAttempt');
  if (url) {
    localStorage.removeItem('addScreenshotAttempt');
    $('#add-screenshot-url').val(url);
    $(form).submit();
  }


  //
  // Sidebar
  //
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
  

  
  $('.all-posts').parent().append('<span class="load-more"></span>');
	var loadmore = $('.container .load-more');
	var page = 2;
	var loading = false;
	var scrollHandling = {
	    allow: true,
	    reallow: function() {
	        scrollHandling.allow = true;
	    },
	    delay: 400 //(milliseconds) adjust to the highest acceptable value
	};

	$(window).scroll(function() {
		if(!loading && scrollHandling.allow) {
			scrollHandling.allow = false;
			setTimeout(scrollHandling.reallow, scrollHandling.delay);
      var viewBottom = $(window).scrollTop() + $(window).height();
			var loadMoreTop = $(loadmore).offset().top;
      var offset = loadMoreTop - viewBottom;

			if(500 > offset) {
				loading = true;
				var data = {
					action: 'ajax_load_more',
					page: page,
					query: php_data.posts_query,
				};
				$.post(php_data.ajax_url, data, function(res) {
					if(res.success) {
            var $html = $(res.data)
				    $('.all-posts').append($html);
				    $('.all-posts').isotope('appended', $html);
//				    $('.all-posts').append(button);
						page = page + 1;
						loading = false;
					} else {
						// console.log(res);
					}
				}).fail(function(xhr, textStatus, e) {
					// console.log(xhr.responseText);
				});

			}
		}
  });
});
