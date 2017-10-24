<?php
  /*
     Plugin Name: Inhuman Ads
   */

  //require_once(plugin_dir_path(__FILE__) . 'debug.php');
  require_once(plugin_dir_path(__FILE__) . 'api_publish.php');
  require_once(plugin_dir_path(__FILE__) . 'api_users.php');
  require_once(plugin_dir_path(__FILE__) . 'api_screenshots.php');
  require_once(plugin_dir_path(__FILE__) . 'post_types.php');
  require_once(plugin_dir_path(__FILE__) . 'post_metadata.php');
  require_once(plugin_dir_path(__FILE__) . 'admin_columns.php');
  require_once(plugin_dir_path(__FILE__) . 'email_confirmation.php');
  require_once(plugin_dir_path(__FILE__) . 'search_custom_fields.php');
  require_once(plugin_dir_path(__FILE__) . 'search_post_authors.php');
  require_once(plugin_dir_path(__FILE__) . 'login.php');

  //
  // No extra <p> tags on plain cards
  //
  add_filter('the_content', 'inhuman_content_filter', 9);
  function inhuman_content_filter($content) {
    global $post;
    $type = get_post_meta($post->ID, 'inhuman_meta_key_type', true);
    if ("plain" == $type or "background" == $type) {
      remove_filter('the_content', 'wpautop');
      remove_filter('the_excerpt', 'wpautop');
    }
    return $content;
  }

?>
