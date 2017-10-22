jQuery(document).ready(function($) {

  "use strict";

  $(".top-posts .arrow-left").click(function() {
    $(".top-posts-viewport").animate({scrollLeft: 0}, 1000);
  });
  $(".top-posts .arrow-right").click(function() {
    $(".top-posts-viewport").animate({
      scrollLeft: $(".top-posts-viewport")[0].scrollWidth
    }, 1000);
  });
  
  let handleShowSpam = function(e) {
      e.preventDefault();
      $(this).parent().hide().next().show().removeClass("hide");
//		  $('.all-posts').isotope('layout');
  }
  $(".spam-shield a").click(handleShowSpam);

//  $(window).scroll(function() {
//    // FIXME: incorrectly removes smaller on secondary pages after scrolling down & back up
//    // 234 is the height of the extended header/hero
//    (window.pageYOffset || document.documentElement.scrollTop) > 234?
//      $("header").addClass("smaller") :
//      $("header").removeClass("smaller");
//  });

  var isotope_opts = {
    itemSelector: '.card',
    layoutMode: 'masonry',
    masonry: {
      columnWidth: '.grid-sizer',
      gutter: '.gutter-sizer',
      percentPosition: true
    }
  };
//  $('.popular-posts').isotope(isotope_opts);
//  $('.funny-posts').isotope(isotope_opts);
//  $('.angry-posts').isotope(isotope_opts);
//  $('.sad-posts').isotope(isotope_opts);
//  $('.all-posts').isotope(isotope_opts);

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

	let paginateOnScroll = function() {
		if(!loading && scrollHandling.allow) {
			scrollHandling.allow = false;
			setTimeout(scrollHandling.reallow, scrollHandling.delay);

      if ($(loadmore).isInViewport({tolerance: 200})) {
        console.log('loading more... page: ' + page);
				loading = true;
				var data = {
					action: 'ajax_load_more',
					paged: page
				};
				$.post($('#php_data_ajax_url').val(), data, function(res) {
					if(res.success) {
            if ("" != res.data) {
              var $html = $(res.data)
				      $('.all-posts').append($html).find(".spam-shield a").click(handleShowSpam);
//				      $('.all-posts').isotope('appended', $html);
						  page = page + 1;
						  loading = false;
            } else {
              // no more items, stop paginating
              $(window).off("scroll", paginateOnScroll);
				      $('.the-end').show();
            }
					} else {
						console.log('failed to load more content: ' + res);
					}
				}).fail(function(xhr, textStatus, e) {
					console.log(xhr.responseText);
				});

			}
		}
  };
  $(window).scroll(paginateOnScroll);

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
