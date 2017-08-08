<?php

  require_once(plugin_dir_path(__FILE__) . 'vendor/autoload.php');
  use PHPHtmlParser\Dom;

  //
  // Add screenshot (XHR from add-on method)
  //
  function inhuman_add_screenshot() {
    $data = json_decode(file_get_contents('php://input'), true);
    $shotId = $data["shotId"];
    $shotDomain = $data["shotDomain"];
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
  // Update/publish screenshot (XHR from page)
  //
  function inhuman_update_screenshot() {
    $raw = json_decode(file_get_contents('php://input'), true);
    $post_id = sanitize_text_field($raw["post_id"]);
    $author = get_post_field('post_author', $post_id);

    if ($author != get_current_user_id()) {
      http_response_code(401);
      die();
    } elseif (false) {
      // FIXME: check if flagged for moderation / as spam
    } else {
      wp_update_post(array(
        'ID' => $post_id,
        'post_title' => sanitize_text_field($raw["caption"]),
        'post_status' => 'publish',
        'meta_input' => array(
          'inhuman_meta_status' => 'publish',
          'inhuman_meta_ad_brand' => sanitize_text_field($raw["brand"]),
          'inhuman_meta_offensive' => sanitize_text_field($raw["offensive"])
        )));

      echo json_encode(array('success' => true));

      die();
    }
  }
  add_action('wp_ajax_inhuman_update_screenshot', 'inhuman_update_screenshot');

  //
  // Login
  //
  function inhuman_register() {
    $data = json_decode(file_get_contents('php://input'), true);

    $user_id = username_exists($data["deviceId"]);
    if (!$user_id) {
	    $user_id = wp_create_user($data["deviceId"], $data["secret"]);
      wp_update_user(array('ID' => $user_id, 'display_name' => 'Anonymous User'));
    } else {
      http_response_code(403); // user already exists
      die();
    }

    inhuman_login();
  }
  add_action('wp_ajax_nopriv_inhuman_register', 'inhuman_register');

  function inhuman_login() {
    $data = json_decode(file_get_contents('php://input'), true);
    $user_id = username_exists($data["deviceId"]);
    if (!$user_id) {
      http_response_code(404); // unknown user
      die();
    } else {
      $info = array();
      $info['user_login'] = $data["deviceId"];
      $info['user_password'] = $data["secret"];
      $info['remember'] = true;
      $user_signon = wp_signon($info, false);
      if (is_wp_error($user_signon)){
        http_response_code(403); // wrong password
        echo json_encode(array('loggedin'=>false, 'message'=>__('Wrong username or password.')));
      } else {
        echo json_encode(array('loggedin'=>true, 'message'=>__('Login successful.')));
      }
      die();
    }
  }
  add_action('wp_ajax_inhuman_login', 'inhuman_login');
  add_action('wp_ajax_nopriv_inhuman_login', 'inhuman_login');

  function _inhuman_meta_counter_incr($post_id, $key) {
    $cur = get_post_meta($post_id, $key, true);
    if (!$cur)
      $cur = 0;
    return update_post_meta($post_id, $key, $cur + 1);
  }

  function _inhuman_like($post_id, $emoji) {
    return _inhuman_meta_counter_incr($post_id, "inhuman_meta_like_" . $emoji . "_count") &&
           _inhuman_meta_counter_incr($post_id, "inhuman_meta_total_like_count");
  }

  function inhuman_like() {
    $raw = json_decode(file_get_contents('php://input'), true);
    $post_id = sanitize_text_field($raw["post_id"]);
    $emoji = $raw["emoji"];
    $success = true;

    switch ($emoji) {
      case "funny":
        $success = _inhuman_like($post_id, "funny");
        break;
      case "angry":
        $success = _inhuman_like($post_id, "angry");
        break;
      case "sad":
        $success = _inhuman_like($post_id, "sad");
        break;
      case "huh":
        $success = _inhuman_like($post_id, "huh");
        break;
      default:
        http_response_code(404); // unknown emoji
        $success = false;
    }
    echo json_encode(array('success'=>$success));
    die();
  }
  add_action('wp_ajax_inhuman_like', 'inhuman_like');
  add_action('wp_ajax_nopriv_inhuman_like', 'inhuman_like');

  //
  // Process form to set username (&email?)
  //

  function inhuman_user_setup() {
    $raw = json_decode(file_get_contents('php://input'), true);
    $name = sanitize_text_field($raw["name"]);
    $email = sanitize_text_field($raw["email"]);

    if (is_user_logged_in()) {
      $user_id = get_current_user_id();
      wp_update_user(array(
        'ID' => $user_id,
        'display_name' => $name,
        'email' => $email
      ));

      update_user_meta($user_id, "inhuman_user_score_today", 0);
      update_user_meta($user_id, "inhuman_user_score_week", 0);
      update_user_meta($user_id, "inhuman_user_score_forever", 0);

      update_user_meta($user_id, "inhuman_user_complete", true);
    }
    echo json_encode(array('success'=>true));
    die();
  }
  add_action('wp_ajax_inhuman_user_setup', 'inhuman_user_setup');

?>
