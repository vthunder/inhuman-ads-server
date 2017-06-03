<?php
  wp_enqueue_script('require', get_bloginfo('template_directory') . '/vendor/requirejs/require.js');
  wp_enqueue_script('main', get_bloginfo('template_directory') . '/js/main.js');
  wp_localize_script('main', 'php_data', array(
    'base_uri' => get_template_directory_uri()
  ));
  wp_footer();
?>
</body>
</html>
