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
  // Pagination
  //
  function inhuman_image_size_override() {
    return array( 825, 510 );
  }

  function inhuman_ajax_pagination() {
    $query_vars = json_decode( stripslashes( $_POST['query_vars'] ), true );

    $query_vars['paged'] = $_POST['page'];


    $posts = new WP_Query( $query_vars );
    $GLOBALS['wp_query'] = $posts;

    add_filter( 'editor_max_image_size', 'inhuman_image_size_override' );

    if( ! $posts->have_posts() ) { 
      get_template_part( 'content', 'none' );
    }
    else {
      while ( $posts->have_posts() ) { 
        $posts->the_post();
        get_template_part( 'content', get_post_format() );
      }
    }
    remove_filter( 'editor_max_image_size', 'inhuman_image_size_override' );

    the_posts_pagination( array(
      'prev_text'          => __( 'Previous page', 'inhuman-ads' ),
      'next_text'          => __( 'Next page', 'inhuman-ads' ),
      'before_page_number' => '<span class="meta-nav screen-reader-text">' . __( 'Page', 'inhuman-ads' ) . ' </span>',
    ) );

    die();
  }
  add_action('wp_ajax_nopriv_ajax_pagination', 'inhuman_ajax_pagination');
  add_action('wp_ajax_ajax_pagination', 'inhuman_ajax_pagination');

  //
  // Login
  //
  function inhuman_register() {
    $data = json_decode(file_get_contents('php://input'), true);

    $user_id = username_exists($data["deviceId"]);
    if (!$user_id) {
	    $user_id = wp_create_user($data["deviceId"], $data["secret"]);
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

?>
