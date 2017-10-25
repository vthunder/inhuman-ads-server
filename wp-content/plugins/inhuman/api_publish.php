<?php

  require_once(plugin_dir_path(__FILE__) . 'offensive_domains.php');
  require_once(plugin_dir_path(__FILE__) . 'vendor/autoload.php');
  use PHPHtmlParser\Dom;

  // 
  // Redirect logged out users trying to post an ad
  // 
  function inhuman_template_redirect () {
	  if (is_page('submit') && !is_user_logged_in()) {
      $url = add_query_arg('orig_request', urlencode($_SERVER[REQUEST_URI]),
                           home_url('/login/'));
		  wp_safe_redirect(esc_url($url));
		  exit();
	  }
  }
  add_action('template_redirect', 'inhuman_template_redirect');

  //
  // Add screenshot
  //
  function inhuman_add_screenshot() {
    $shotId = $_POST["shotId"];
    $shotDomain = $_POST["shotDomain"];
    $url = sanitize_text_field("https://screenshots.firefox.com/{$shotId}/{$shotDomain}");

    $dom = new Dom;
    $dom->loadFromUrl($url);
    $domain = $dom->find('.shot-subtitle .subtitle-link')[0]->text;

    $post_id = wp_insert_post(array(
      'post_title'    => '',
      'post_content'  => '',
      'post_status'   => 'publish',
      'post_author'   => get_current_user_id(),
      'post_type' => 'inhuman_screenshot',
      'meta_input' => array(
        'inhuman_meta_type' => 'screenshot',
        'inhuman_meta_status' => 'draft',
        'inhuman_meta_shot_url' => $url,
        'inhuman_meta_publisher_domain' => $domain
      )
    ));

    // Automatically flag as spam/inappropriate if domain is likely to
    // contain inappropriate material (e.g., porn sites)
    if (likely_offensive_domain($domain)) {
      _inhuman_report($post_id);
      _inhuman_flag_confirm($post_id);
    }

    // download screenshot image, add is as an attachment, and set it
    // as the featured image (thumbnail)

    $img = $dom->find('#clipImage')[0];
    media_sideload_image($img->src, $post_id, "Screenshot");

    // finds the last image added to the post attachments
    $attachments = get_posts(array(
      'numberposts' => '1',
      'post_parent' => $post_id,
      'post_type' => 'attachment',
      'post_mime_type' => 'image',
      'order' => 'ASC'));

    if(sizeof($attachments) > 0){
      set_post_thumbnail($post_id, $attachments[0]->ID);
    }

    echo json_encode(array('success' => true,
                           'url' => get_permalink($post_id),
                           'editUrl' => get_permalink($post_id) . "?edit"
    ));

    die();
  }
  add_action('wp_ajax_inhuman_add_screenshot', 'inhuman_add_screenshot');

  //
  // Update/publish screenshot
  //
  function inhuman_update_screenshot() {
    $post_id = sanitize_text_field($_POST["post_id"]);
    $author = get_post_field('post_author', $post_id);

    if ($author != get_current_user_id()) {
      http_response_code(401);
      die();
    } elseif (false) {
      // FIXME: check if flagged for moderation / as spam
    } else {
      $offensive = sanitize_text_field($_POST["offensive"]);
      // HACK: something is getting lost in encoding, restore boolean
      if ($offensive == "false")
        $offensive = false;
      wp_update_post(array(
        'ID' => $post_id,
        'post_title' => sanitize_text_field($_POST["caption"]),
        'post_status' => 'publish',
        'meta_input' => array(
          'inhuman_meta_status' => 'publish',
          'inhuman_meta_ad_brand' => sanitize_text_field($_POST["brand"]),
          'inhuman_meta_offensive' => $offensive
        )));
      if ($offensive) {
        _inhuman_report($post_id);
        _inhuman_flag_confirm($post_id);
      }

      echo json_encode(array('success' => true));

      die();
    }
  }
  add_action('wp_ajax_inhuman_update_screenshot', 'inhuman_update_screenshot');

  //
  // Remove screenshot
  //
  function inhuman_delete_screenshot() {
    $post_id = sanitize_text_field($_POST["post_id"]);
    $author_id = get_post_field('post_author', $post_id);
    $success = false;

    if ($author_id == get_current_user_id()) {
      if (wp_delete_post($post_id))
        $success = true;
    } else {
      http_response_code(401);
      die();
    }

    echo json_encode(array('success' => $success));
    die();
  }
  add_action('wp_ajax_inhuman_delete_screenshot', 'inhuman_delete_screenshot');

?>
