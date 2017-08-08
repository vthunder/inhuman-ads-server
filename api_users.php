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

  //
  // Regularly reset daily/weekly high scores
  //

  function inhuman_activation() {
    if (!wp_next_scheduled('inhuman_daily_users_event' )) {
	    wp_schedule_event(time(), 'daily', 'inhuman_daily_users_event');
    }
  }
  register_activation_hook(__FILE__, 'inhuman_activation');

  function inhuman_deactivation() {
	  wp_clear_scheduled_hook('inhuman_daily_users_event');
  }
  register_deactivation_hook(__FILE__, 'inhuman_deactivation');

  function inhuman_daily_high_scores_reset() {
    $day = date('w');
    $users = get_users();
    foreach ($users as $user) {
      update_user_meta($user->ID, "inhuman_user_score_today", 0);
      if (0 == $day) // Sunday
        update_user_meta($user->ID, "inhuman_user_score_week", 0);
    }
  }
  add_action('inhuman_daily_users_event', 'inhuman_daily_high_scores_reset');

?>
