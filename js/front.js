jQuery(document).ready(function($) {

  "use strict";

  $(window).scroll(function() {
    // FIXME: incorrectly removes smaller on secondary pages after scrolling down & back up
    // 234 is the height of the extended header/hero
    (window.pageYOffset || document.documentElement.scrollTop) > 234?
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

      if ($(loadmore).isInViewport({tolerance: 200})) {
        console.log('loading more... page: ' + page);
				loading = true;
				var data = {
					action: 'ajax_load_more',
					page: page
				};
				$.post(php_data.ajax_url, data, function(res) {
					if(res.success) {console.log(JSON.stringify(res.data));
            var $html = $(res.data)
				    $('.all-posts').append($html);
				    $('.all-posts').isotope('appended', $html);
						page = page + 1;
						loading = false;
					} else {
						console.log('failed to load more content: ' + res);
					}
				}).fail(function(xhr, textStatus, e) {
					console.log(xhr.responseText);
				});

			}
		}
  });
});
