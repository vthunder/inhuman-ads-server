<?php
  add_theme_support('post-thumbnails');

  $inhuman_featured_query = array(
    'post_type' => 'post',
    'meta_query'  => array(
      array(
        'key' => '_inhuman_meta_featured',
        'value' => 'on'
      )
    ),
    'meta_key' => '_inhuman_meta_sort',
    'orderby' => 'meta_value_num',
    'order' => 'ASC',
  );
  $inhuman_posts_query = array(
    'post_type' => array('post', 'inhuman_screenshot'),
    'meta_query'  => array(
      'relation' => 'OR',
      array(
        'key' => '_inhuman_meta_featured',
        'compare' => 'NOT EXISTS',
        'value' => ''
      ),
      array(
        'key' => '_inhuman_meta_featured',
        'value' => ''
      )
    )

  );

  //
  // Server-side variables to make available to JS
  //
  function inhuman_setup_js_vars() {
    wp_localize_script('main', 'php_data', array(
      'base_uri' => get_template_directory_uri(),
      'ajax_url' => admin_url('admin-ajax.php'),
      'nonce' => wp_create_nonce('inhuman-ads-nonce'),
      'posts_query' => $inhuman_posts_query,
      'user_display_name' => wp_get_current_user()->display_name
    ));
  }

  //
  // Enqueue JS & CSS
  //
  function inhuman_enqueue_styles() {
    $tpldir = get_bloginfo('template_directory');
    wp_enqueue_style('default', $tpldir . '/styles/main.css');
    if (is_front_page()) {
      wp_enqueue_style('sidr', $tpldir . '/vendor/sidr/dist/stylesheets/jquery.sidr.dark.min.css' );
    }
    wp_enqueue_style('buttons', $tpldir . '/vendor/buttons/css/buttons.css' );
    wp_enqueue_style('font-awesome', $tpldir . '/vendor/font-awesome/css/font-awesome.css' );
  }
  function inhuman_enqueue_scripts() {
    $tpldir = get_bloginfo('template_directory');
    wp_enqueue_script('jquery', $tpldir . '/vendor/jquery/dist/jquery.min.js');
    wp_enqueue_script('sidr', $tpldir . '/vendor/sidr/dist/jquery.sidr.min.js', array('jquery'));
    if (is_front_page()) {
      wp_enqueue_script('jquery-isotope', $tpldir . '/vendor/isotope/dist/isotope.pkgd.min.js', array('jquery'));
      wp_enqueue_script('main', $tpldir . '/js/front.js', array('jquery', 'jquery-isotope', 'sidr'));
    } else {
      wp_enqueue_script('main', $tpldir . '/js/post.js', array('jquery', 'sidr'));
    }
    inhuman_setup_js_vars();
  }
  add_action('wp_enqueue_scripts', 'inhuman_enqueue_styles');
  add_action('wp_enqueue_scripts', 'inhuman_enqueue_scripts');

  //
  // AJAX Pagination
  //
  function load_more() {
    $args = array(
      'post_type' => array('post', 'inhuman_screenshot'),
      'meta_query'  => array(
        'relation' => 'OR',
        array(
          'key' => '_inhuman_meta_featured',
          'compare' => 'NOT EXISTS',
          'value' => ''
        ),
        array(
          'key' => '_inhuman_meta_featured',
          'value' => ''
        )
      )
    );
    $args['paged'] = esc_attr($_POST['page']);
    //	  $args['post_status'] = 'publish';

    ob_start();

    $loop = new WP_Query($args);
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
