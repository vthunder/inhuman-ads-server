<?php
  require_once(plugin_dir_path(__FILE__) . 'functions/create-pages.php');
  require_once(plugin_dir_path(__FILE__) . 'functions/shortcodes.php');

  function tpldir() {
    echo get_template_directory_uri();
  }

  function user() {
    return wp_get_current_user();
  }

  function inhuman_setup_theme() {
    add_theme_support('post-thumbnails');
    add_image_size('screenshot_preview', 100, 100, true);
  }

  add_action('after_setup_theme', 'inhuman_setup_theme');

  // Disable admin bar for all users
  add_filter('show_admin_bar', '__return_false');

  // Allow searching by display name in users table
  add_filter('user_search_columns', 'inhuman_user_search_columns' , 10, 3);
  function inhuman_user_search_columns($search_columns, $search){

    if(!in_array('display_name', $search_columns)){
      $search_columns[] = 'display_name';
    }
    return $search_columns;
  }

  function inhuman_users_with_display_name($display_name) {
    $user_ids = [];
    $user_query = new WP_User_Query([
      'search' => $display_name,
      'search_fields' => ['display_name']]);
    if (!empty($user_query->results)) {
	    foreach ($user_query->results as $user) {
        $user_ids[] = $user->ID;
	    }
    }
    return $user_ids;
  }

  function inhuman_anon_user_ids() {
    return inhuman_users_with_display_name('Anonymous User');
  }

  function inhuman_query($type, $page = null) {
    $query = array(
      'post_type' => array('inhuman_screenshot'),
      'meta_query'  => array(
        array(
          'key' => 'inhuman_meta_status',
          'value' => 'publish'
        ),
        array(
          'relation' => 'OR',
          array(
            'key' => 'inhuman_meta_featured',
            'compare' => 'NOT EXISTS',
            'value' => ''
          ),
          array(
            'key' => 'inhuman_meta_featured',
            'value' => ''
          )
        )
      )
    );

    switch ($type) {
      case "popular":
        $query['meta_key'] = 'inhuman_meta_total_like_count';
        $query['orderby'] = 'meta_value_num';
        break;
      case "funny":
        $query['meta_key'] = 'inhuman_meta_like_funny_count';
        $query['orderby'] = 'meta_value_num';
        break;
      case "angry":
        $query['meta_key'] = 'inhuman_meta_like_angry_count';
        $query['orderby'] = 'meta_value_num';
        break;
      case "sad":
        $query['meta_key'] = 'inhuman_meta_like_sad_count';
        $query['orderby'] = 'meta_value_num';
        break;
      case "huh":
        $query['meta_key'] = 'inhuman_meta_like_huh_count';
        $query['orderby'] = 'meta_value_num';
        break;
      case "blog":
        $query = array(
          'post_type' => array('post')
        );
      default:
        break;
    }

    if ($page) {
      $query['paged'] = $page;
    }

    return $query;
  }

  function queryAllIds($query_type) {
    $query = inhuman_query($query_type);
    $query['posts_per_page'] = -1;
    $posts = get_posts($query);
    $ids = array();
    foreach ($posts as $post) {
      $ids[] = $post->ID;
    }
    return $ids;
  }

  function prevNextIds($post_id, $query_type) {
    $ids = queryAllIds($query_type);
    $thisindex = array_search($post_id, $ids);
    if (0 == $thisindex)
      return array('prev' => NULL,
                   'next' => $ids[$thisindex + 1]);
    if (count($ids) == $thisindex + 1)
      return array('prev' => $ids[$thisindex - 1],
                   'next' => NULL);
    return array('prev' => $ids[$thisindex - 1],
                 'next' => $ids[$thisindex + 1]);
  }

  function add_query_vars_filter($vars){
    $vars[] = "e";
    return $vars;
  }
  add_filter('query_vars', 'add_query_vars_filter');

  //
  // Server-side variables to make available to JS
  //
  function inhuman_setup_js_vars() {
    $vars = array(
      'post_id' => get_the_ID(),
      'base_uri' => get_template_directory_uri(),
      'ajax_url' => admin_url('admin-ajax.php'),
      'nonce' => wp_create_nonce('inhuman-ads-nonce'),
      'user_display_name' => wp_get_current_user()->display_name
    );
    foreach ($vars as $key => $value) {
      echo '<input type="hidden" id="php_data_' . $key . '" value="' . $value . '">';
    }
  }

  //
  // Enqueue JS & CSS
  //
  function inhuman_enqueue_styles() {
    $tpldir = get_bloginfo('template_directory');
    wp_enqueue_style('wp-default', $tpldir . '/styles/wp-default.css');
    wp_enqueue_style('default', $tpldir . '/styles/main.css');
    wp_enqueue_style('sidr', $tpldir . '/vendor/sidr/dist/stylesheets/jquery.sidr.bare.css' );
    wp_enqueue_style('buttons', $tpldir . '/vendor/buttons/css/buttons.css' );
    wp_enqueue_style('font-awesome', $tpldir . '/vendor/font-awesome/css/font-awesome.css' );
  }
  function inhuman_enqueue_scripts() {
    $tpldir = get_bloginfo('template_directory');
    $vendor = $tpldir . '/vendor';
    $jsdir = $tpldir . '/js';

    $scripts = [
      'vendor' => [
        'jquery' => [
          'file' => "$vendor/jquery/dist/jquery.min.js",
          'deps' => []
        ],
        'sidr' => [
          'file' => "$vendor/sidr/dist/jquery.sidr.min.js",
          'deps' => ['jquery']
        ],
        'dim-bg' => [
          'file' => "$vendor/jquery-dim-background/jquery.dim-background.min.js",
          'deps' => ['jquery']
        ],
        'jquery-isotope' => [
          'file' => "$vendor/isotope/dist/isotope.pkgd.min.js",
          'deps' => ['jquery']
        ],
        'jquery-docsize' => [
          'file' => "$vendor/jquery.documentsize/dist/jquery.documentsize.min.js",
          'deps' => ['jquery']
        ],
        'jquery-isinview' => [
          'file' => "$vendor/jquery.isinview/dist/jquery.isinview.min.js",
          'deps' => ['jquery', 'jquery-docsize']
        ]
      ],
      'components' => [
        'analytics' => [
          'file' => "$jsdir/analytics.js",
          'deps' => []
        ],
        'header' => [
          'file' => "$jsdir/header.js",
          'deps' => ['jquery']
        ],
        'sidebar' => [
          'file' => "$jsdir/sidebar.js",
          'deps' => ['jquery', 'sidr', 'dim-bg']
        ]
      ],
      'main' => [
        'front' => [
          'file' => "$jsdir/front.js",
          'deps' => ['jquery', 'jquery-isotope', 'jquery-isinview',
                     'analytics', 'header', 'sidebar']
        ],
        'post' => [
          'file' => "$jsdir/post.js",
          'deps' => ['jquery', 'analytics', 'header', 'sidebar']
        ]
      ]
    ];

    // Register all scripts
    foreach ($scripts as $defs) {
      foreach ($defs as $name => $props) {
        wp_register_script($name, $props['file'], $props['deps']);
      }
    }

    // Single pages load post.js, front page loads front.js
    if (is_single()) {
      wp_enqueue_script('post');
    } else {
      wp_enqueue_script('front');
    }

    // Set some data as hidden fields so it can be accessible to JS
    inhuman_setup_js_vars();
  }
  add_action('wp_enqueue_scripts', 'inhuman_enqueue_styles');
  add_action('wp_enqueue_scripts', 'inhuman_enqueue_scripts');

  //
  // AJAX Pagination
  //
  function load_more() {
    ob_start();

    $loop = new WP_Query(inhuman_query('latest', esc_attr($_POST['paged'])));
    if($loop->have_posts()): while($loop->have_posts()): $loop->the_post();
    get_template_part('card', get_post_format());
	  endwhile; endif; wp_reset_postdata();

	  $data = ob_get_clean();
    wp_send_json_success($data);

    wp_die();
  }
  add_action('wp_ajax_ajax_load_more', 'load_more' );
  add_action('wp_ajax_nopriv_ajax_load_more', 'load_more' );

?>
