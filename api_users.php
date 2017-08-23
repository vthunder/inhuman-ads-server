<?php

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
      $credentials = array();
      $credentials['user_login'] = $data["deviceId"];
      $credentials['user_password'] = $data["secret"];
      $credentials['remember'] = true;
      $user_signon = wp_signon($credentials, false);
      if (is_wp_error($user_signon)){
        http_response_code(403); // wrong password
        echo json_encode(array('loggedin'=>false, 'message'=>__('Wrong username or password.')));
      } else {
        $alias_id = get_user_meta($user_id, "inhuman_user_alias", true);
        if (!empty($alias_id)) {
          $secure_cookie = apply_filters('secure_signon_cookie', true, $credentials);
          wp_set_auth_cookie($alias_id, true, $secure_cookie);
        }
        echo json_encode(array('loggedin'=>true, 'message'=>__('Login successful.')));
      }
      die();
    }
  }
  add_action('wp_ajax_inhuman_login', 'inhuman_login');
  add_action('wp_ajax_nopriv_inhuman_login', 'inhuman_login');

  //
  // Process form to set username & email
  //

  function inhuman_user_default_meta() {
    $user_id = get_current_user_id();

    update_user_meta($user_id, "inhuman_user_score_today", 0);
    update_user_meta($user_id, "inhuman_user_score_week", 0);
    update_user_meta($user_id, "inhuman_user_score_forever", 0);
    update_user_meta($user_id, "inhuman_user_likes", array());

    update_user_meta($user_id, "inhuman_user_complete", true);
  }

  function inhuman_user_exists($name) {
    global $wpdb;

    // We allow duplicates called "Anonymous user"
    if ($name == "Anonymous User")
      return false;

    $user_id = get_current_user_id();
    return $wpdb->get_var($wpdb->prepare(
      "SELECT COUNT(ID) FROM $wpdb->users WHERE display_name = %s AND ID <> %d",
      $name, $user_id));
  }

  function inhuman_user_setup() {
    $raw = json_decode(file_get_contents('php://input'), true);
    $name = sanitize_text_field($raw["name"]);
    $email = sanitize_text_field($raw["email"]);
    $success = false;
    $error = '';

    if (is_user_logged_in()) {
      if (inhuman_user_exists($name)) {
        $error = "Name taken";
      } else {
        wp_update_user(array(
          'ID' => get_current_user_id(),
          'display_name' => $name,
          'user_email' => $email
        ));

        inhuman_user_default_meta();

        $success = true;
      }

      echo json_encode(array('success' => $success, 'error' => $error));
    }
    die();
  }
  add_action('wp_ajax_inhuman_user_setup', 'inhuman_user_setup');

  function inhuman_user_setup_existing() {
    $raw = json_decode(file_get_contents('php://input'), true);
    $email = sanitize_text_field($raw["email"]);

    if (is_user_logged_in()) {
      $user_id = get_current_user_id();

      if (!empty(get_user_by('email', $email))) {
        update_user_meta($user_id, "inhuman_confirming_email", $email);
        if (null !== inhuman_send_email_confirmation($user_id, $email)) {
          echo json_encode(array('success' => true));
        } else {
          echo json_encode(array('success' => false, 'error' => 'Could not send email'));
        }
      } else {
        echo json_encode(array('success' => false, 'error' => 'Email not found'));
      }
    }
    die();
  }
  add_action('wp_ajax_inhuman_user_setup_existing', 'inhuman_user_setup_existing');

  function inhuman_user_verify_token() {
    $raw = json_decode(file_get_contents('php://input'), true);
    $token = sanitize_text_field($raw["token"]);
    $success = false;

    if (is_user_logged_in()) {
      $user_id = get_current_user_id();
      if (inhuman_check_email_confirmation($user_id, $token)) {
        $email = get_user_meta($user_id, "inhuman_confirming_email", true);
        $prev_user = get_user_by('email', $email);
        update_user_meta($user_id, "inhuman_user_alias", $prev_user->ID);
        $success = true;
      }
    }
    echo json_encode(array('success' => $success));
    die();
  }
  add_action('wp_ajax_inhuman_user_verify_token', 'inhuman_user_verify_token');

  //
  // Regularly reset daily/weekly high scores
  //
  function inhuman_daily_high_scores_reset() {
    $day = date('w');
    $users = get_users();
    foreach ($users as $user) {
      update_user_meta($user->ID, "inhuman_user_score_today", 0);
      if (0 == $day) // Sunday
        update_user_meta($user->ID, "inhuman_user_score_week", 0);
    }

    $shots = new WP_Query(array(
      'post_type' => array('inhuman_screenshot')
    ));
		if ($shots->have_posts()) {
      while ($shots->have_posts()) {
        $shots->the_post();
        update_post_meta($shots->ID, "inhuman_meta_weekly_like_count", 0);
      }
    }

  }

  function inhuman_cron_job() {
    if (!wp_next_scheduled('inhuman_daily_high_scores_reset' )) {
	    wp_schedule_event(time(), 'daily', 'inhuman_daily_high_scores_reset');
    }
  }
  add_action('wp', 'inhuman_cron_job');

?>
