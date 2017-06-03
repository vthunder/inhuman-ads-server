require.config({
  baseUrl: php_data.base_uri,
  paths: {
      'isotope': 'vendor/isotope/dist/isotope.pkgd.min'
  },
})

require(['isotope'],
	function (Isotope) {
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
})
