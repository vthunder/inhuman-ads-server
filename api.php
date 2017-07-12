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
    $img = $dom->find('#clipImage')[0];

    $post_id = wp_insert_post(array(
      'post_title'    => '',
      'post_content'  => '',
      'post_status'   => 'publish',
      'post_author'   => get_current_user_id(),
      'post_type' => 'inhuman_screenshot',
      'meta_input' => array(
        '_inhuman_meta_type' => 'screenshot',
        '_inhuman_meta_screenshot' => $img->src
      )
    ));

    $image = media_sideload_image($img->src, $post_id, "Screenshot");

    echo json_encode(array('success'=>true,
                           'url'=>get_permalink($post_id),
                           'editUrl'=>get_permalink($post_id) . "?edit"
    ));

    die();
  }
  add_action('wp_ajax_inhuman_add_screenshot', 'inhuman_add_screenshot');

  
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
