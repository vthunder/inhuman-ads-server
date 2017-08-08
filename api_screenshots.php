<?php

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

    // bump score of screenshot owner
    $user_id = get_post_field('post_author', $post_id);
    _inhuman_bump_high_score($user_id, 1);

    echo json_encode(array('success'=>$success));
    die();
  }
  add_action('wp_ajax_inhuman_like', 'inhuman_like');
  add_action('wp_ajax_nopriv_inhuman_like', 'inhuman_like');

?>
