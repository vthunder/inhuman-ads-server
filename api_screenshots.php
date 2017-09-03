<?php

  $emojis = array('funny', 'angry', 'sad', 'huh');

  function _inhuman_bump_high_score($user_id, $amount) {
    $day = get_user_meta($user_id, "inhuman_user_score_today", true);
    $week = get_user_meta($user_id, "inhuman_user_score_week", true);
    $forever = get_user_meta($user_id, "inhuman_user_score_forever", true);

    update_user_meta($user_id, "inhuman_user_score_today", ($day || 0) + $amount);
    update_user_meta($user_id, "inhuman_user_score_week", ($week || 0) + $amount);
    update_user_meta($user_id, "inhuman_user_score_forever", ($forever || 0) + $amount);
  }

  function _inhuman_meta_counter_incr($post_id, $key) {
    $cur = get_post_meta($post_id, $key, true);
    if (!$cur)
      $cur = 0;
    return update_post_meta($post_id, $key, $cur + 1);
  }

  function _inhuman_like($post_id, $emoji) {
    return _inhuman_meta_counter_incr($post_id, "inhuman_meta_like_" . $emoji . "_count") &&
           _inhuman_meta_counter_incr($post_id, "inhuman_meta_weekly_like_count") &&
           _inhuman_meta_counter_incr($post_id, "inhuman_meta_total_like_count");
  }

  function inhuman_like() {
    global $emojis;
    $raw = json_decode(file_get_contents('php://input'), true);
    $post_id = sanitize_text_field($raw["post_id"]);
    $emoji = $raw["emoji"];
    $success = false;

    foreach ($emojis as $e) {
      if ($emoji == $e) {
        $success = _inhuman_like($post_id, $e);
        break;
      }
    }
    if ($success) {
      // bump score of screenshot owner
      $author_id = get_post_field('post_author', $post_id);
      _inhuman_bump_high_score($author_id, 1);

      // track user likes if user is logged in
      if (is_user_logged_in()) {
        $user_id = get_current_user_id();
        $likes = get_user_meta($user_id, "inhuman_user_likes", true);
        if (!$likes[$post_id]) {
          $likes[$post_id] = array();
          foreach ($emojis as $e) {
            $likes[$post_id][$e] = 0;
          }
        }
        $likes[$post_id][$emoji] = $likes[$post_id][$emoji] + 1;
        update_user_meta($user_id, "inhuman_user_likes", $likes);

        // track users by post in case we want that later
        $users_liked = get_post_meta($post_id, 'inhuman_like_user_list', true);
        if (!$users_liked)
          $users_liked = array();
        $users_liked[$user_id] = true;
        update_post_meta($post_id, 'inhuman_like_user_list', $users_liked);
      }
    } else {
      http_response_code(404); // unknown emoji
    }

    echo json_encode(array('success'=>$success));
    die();
  }
  add_action('wp_ajax_inhuman_like', 'inhuman_like');
  add_action('wp_ajax_nopriv_inhuman_like', 'inhuman_like');

  function _inhuman_report($post_id) {
    $count = get_post_meta($post_id, 'inhuman_flagged_count', true);
    if ($count == '')
      $count = 0;
    update_post_meta($post_id, 'inhuman_flagged_count', $count + 1);

    $status = get_post_meta($post_id, 'inhuman_flagged_status', true);
    if ($status != 'Confirmed' && $status != 'Cleared') {
      // keep a separate count of pre-review flags for better admin panel sorting
      $pre_count = get_post_meta($post_id, 'inhuman_flagged_unreviewed_count', true);
      if ($pre_count == '')
        $pre_count = 0;
      update_post_meta($post_id, 'inhuman_flagged_unreviewed_count', $pre_count + 1);
      update_post_meta($post_id, 'inhuman_flagged_status', 'Flagged');
    }
  }
  function inhuman_report() {
    $raw = json_decode(file_get_contents('php://input'), true);
    $post_id = sanitize_text_field($raw["post_id"]);

    _inhuman_report($post_id);

    echo json_encode(array('success'=>true));
    die();
  }
  add_action('wp_ajax_inhuman_report', 'inhuman_report');
  add_action('wp_ajax_nopriv_inhuman_report', 'inhuman_report');

  // Admin page actions

  add_action('admin_post_inhuman_flag_confirm', 'inhuman_flag_confirm');
  function _inhuman_flag_confirm($post_id) {
    // setting unreviewed_count negative keeps it in list sorted at bottom
    update_post_meta($post_id, 'inhuman_flagged_unreviewed_count', -1);
    update_post_meta($post_id, 'inhuman_flagged_status', 'Confirmed');
  }
  function inhuman_flag_confirm() {
    $post_id = sanitize_text_field($_GET["post"]);

    _inhuman_flag_confirm($post_id);

    wp_redirect(admin_url('edit.php?post_type=inhuman_screenshot&orderby=flag'));
  }

  add_action('admin_post_inhuman_flag_clear', 'inhuman_flag_clear');
  function inhuman_flag_clear() {
    $post_id = sanitize_text_field($_GET["post"]);

    // setting unreviewed_count negative keeps it in list sorted at bottom
    update_post_meta($post_id, 'inhuman_flagged_unreviewed_count', -2);
    update_post_meta($post_id, 'inhuman_flagged_status', 'Cleared');

    wp_redirect(admin_url('edit.php?post_type=inhuman_screenshot&orderby=flag'));
  }

?>
