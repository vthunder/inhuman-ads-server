require.config({
  baseUrl: php_data.base_uri,
  paths: {
    "isotope": 'vendor/isotope/dist/isotope.pkgd.min',
    "jquery": 'vendor/jquery/dist/jquery.min',
    "serializeForm": 'vendor/jquery-serializeForm/dist/jquery-serializeForm.min'
  },
  shim: {
    "isotope": ["jquery"],
    "serializeForm": ["jquery"]
  }
});

require(['jquery', 'isotope', 'serializeForm'],
	function ($, Isotope, serializeForm) {
    var grid = document.querySelectorAll('.grid');
    new Isotope(grid[0], {
      itemSelector: '.card',
      layoutMode: 'masonry',
      masonry: {
        columnWidth: '.grid-sizer',
        gutter: '.gutter-sizer',
        percentPosition: true
      }
    });
    new Isotope(grid[1], {
      itemSelector: '.card',
      layoutMode: 'masonry',
      masonry: {
        columnWidth: '.grid-sizer',
        gutter: '.gutter-sizer',
        percentPosition: true
      }
    });

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
  });
