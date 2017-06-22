<div class="footer">
  <div class="footer-right">
    <div class="footer-menu">
  </div>
</div>

<?php
  $tpldir = get_bloginfo('template_directory');
  wp_enqueue_script('jquery', $tpldir . '/vendor/jquery/dist/jquery.min.js');
  wp_enqueue_script('jquery-isotope', $tpldir . '/vendor/isotope/dist/isotope.pkgd.min.js', array('jquery'));
  wp_enqueue_script('jquery-serializeForm', $tpldir . '/vendor/jquery-serializeForm/dist/jquery-serializeForm.min.js', array('jquery'));
  wp_enqueue_script('sidr', $tpldir . '/vendor/sidr/dist/jquery.sidr.min.js', array('jquery'));
  wp_enqueue_script('main', get_bloginfo('template_directory') . '/js/main.js',
                    array('jquery', 'jquery-isotope', 'jquery-serializeForm', 'sidr'));
  inhuman_setup_js_vars();
  wp_footer();
?>

</body>
</html>
