<?php

  //
  // Add images to screenshot admin page
  //

  add_image_size('screenshot_preview', 100, 100, true);

  function inhuman_admin_column_image($post_id) {
    $image_id = get_post_thumbnail_id($post_id);
    if ($image_id) {
      $image = wp_get_attachment_image_src($image_id, 'screenshot_preview');
      return $image[0];
    }
  }

  add_filter('manage_inhuman_screenshot_posts_columns', 'inhuman_admin_screenshots_columns_head');
  function inhuman_admin_screenshots_columns_head($defaults) {
    $defaults['image'] = 'Screenshot Image';
    return $defaults;
  }
  
  add_action('manage_inhuman_screenshot_posts_custom_column', 'inhuman_admin_screenshots_columns_content', 10, 2);
  function inhuman_admin_screenshots_columns_content($column, $post_id) {
    if ($column == 'image') {
      $image = inhuman_admin_column_image($post_id);
      if ($image)
        echo '<img src="' . $image . '" />';
    }
  }

  //
  // Add flagged status to screenshot admin page
  //

  add_filter('manage_inhuman_screenshot_posts_columns', 'inhuman_flagged_table_head');
  function inhuman_flagged_table_head($defaults) {
    $defaults['flag'] = 'Flagged';
    return $defaults;
  }
  
  add_action('manage_inhuman_screenshot_posts_custom_column', 'inhuman_flagged_table_content', 10, 2);
  function inhuman_flagged_table_content($column, $post_id) {
    if ($column == 'flag') {
      $flag_status = get_post_meta($post_id, "inhuman_flagged_status", true);
      $flag_count = get_post_meta($post_id, "inhuman_flagged_count", true);
      if ($flag_count == '')
        $flag_count = 0;
      if ($flag_status != '')
        echo "$flag_status ($flag_count)";
    }
  }

  add_filter('post_row_actions', 'inhuman_flag_action_links', 10, 2);
  function inhuman_flag_action_links($actions, $post) {
    if ($post->post_type != "inhuman_screenshot")
      return $actions;

	  $actions['confirm'] = '<a class="inhuman_flag_confirm" href="' . admin_url("admin-post.php?post_type=inhuman_screenshot&action=inhuman_flag_confirm&post=$post->ID") . '">Spam</a>';
	  $actions['clear'] = '<a class="inhuman_flag_clear" href="' . admin_url("admin-post.php?post_type=inhuman_screenshot&action=inhuman_flag_clear&post=$post->ID") . '">Not spam</a>';

	  return $actions;
  }

  add_filter('manage_edit-inhuman_screenshot_sortable_columns', 'inhuman_sortable_flagged_column');
  function inhuman_sortable_flagged_column($columns) {
    $columns['flag'] = 'flag';
    return $columns;
  }

  add_action('pre_get_posts', 'inhuman_filter_orderby');
  function inhuman_filter_orderby($query) {
    if(! is_admin())
      return;

    $orderby = $query->get('orderby');
    
    if('flag' == $orderby) {
      $query->set('meta_key','inhuman_flagged_unreviewed_count');
      $query->set('orderby','meta_value_num');
    }
  }

  //
  // Add display name to user admin page
  //

  add_filter('manage_users_columns', 'inhuman_admin_users_columns_head');
  function inhuman_admin_users_columns_head($defaults) {
    $new = array();

    unset($defaults['username']);
    unset($defaults['name']);

    foreach($defaults as $key=>$value) {
      if($key=='email')
        $new['display_name'] = 'Display Name';  // put name before email
      $new[$key]=$value;
    }  

    return $new;  
  }
  
  add_action('manage_users_custom_column', 'inhuman_admin_users_columns_content', 10, 3);
  function inhuman_admin_users_columns_content($val, $column, $user_id) {
    if ($column == 'display_name') {
      $name = get_the_author_meta('display_name', $user_id);
      return "<a href=\"/wp-admin/user-edit.php?user_id=$user_id\">$name</a>";
    }
    return $val;
  }

?>
