<?php

  require_once(plugin_dir_path(__FILE__) . 'vendor/autoload.php');
  use PHPHtmlParser\Dom;

  function inhuman_add_screenshot() {
    $dom = new Dom;
    $dom->loadFromUrl(sanitize_text_field($_POST["screenshot-url"]));
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

    die();
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

  function inhuman_image_size_override() {
    return array( 825, 510 );
  }

  function handleContactForm() {

    if($this->isFormSubmitted() && $this->isNonceSet()) {
      if($this->isFormValid()) {
        $this->sendContactForm();
      } else {
        $this->displayContactForm();
      }
    } else {
      $this->displayContactForm();
    }

  }

  function sendContactForm() {
  }

  function isNonceSet() {
    if( isset( $_POST['nonce_field_for_submit_contact_form'] )  &&
        wp_verify_nonce( $_POST['nonce_field_for_submit_contact_form'],  'submit_contact_form' ) ) return true;
    else 
      return false;
  }

  function isFormValid() {
    //Check all mandatory fields are present.
    if ( trim( $_POST['contactname'] ) === '' ) {
      $error = 'Please enter your name.';
      $hasError = true;
    } else if (!filter_var($_POST['contactemail'], FILTER_VALIDATE_EMAIL) )    {
      $error = 'Please enter a valid email.';
      $hasError = true;
    } else if ( trim( $_POST['contactcontent'] ) === '' ) {
      $error = 'Please enter the content.';
      $hasError = true;
    } 

    //Check if any error was detected in validation.
    if($hasError == true) {
      echo $error;
      return false;
    } 
    return true;
  }

  function isFormSubmitted() {
    if( isset( $_POST['submitContactForm'] ) ) return true;
    else return false;
  }

  add_action('wp_ajax_inhuman_add_screenshot', 'inhuman_add_screenshot');

  add_action('wp_ajax_nopriv_ajax_pagination', 'inhuman_ajax_pagination');
  add_action('wp_ajax_ajax_pagination', 'inhuman_ajax_pagination');


  add_action( 'auth0_user_login', 'auth0UserLoginAction', 0,5 );

  function auth0UserLoginAction($user_id, $user_profile, $is_new, $id_token, $access_token) {
  }
?>
