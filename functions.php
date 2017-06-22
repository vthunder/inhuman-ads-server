<?php
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

  function inhuman_setup_js_vars() {
    wp_localize_script('main', 'php_data', array(
      'base_uri' => get_template_directory_uri(),
      'ajax_url' => admin_url('admin-ajax.php'),
      'nonce' => wp_create_nonce('inhuman-ads-nonce'),
      'posts_query' => $inhuman_posts_query,
      'user_display_name' => wp_get_current_user()->display_name
    ));
  }

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
