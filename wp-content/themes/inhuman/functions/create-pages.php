<?php
  // programmatically create some basic pages, and then set Home and Blog
  // setup a function to check if these pages exist
  function the_slug_exists($post_name) {
    global $wpdb;
    if($wpdb->get_row("SELECT post_name FROM wp_posts WHERE post_name = '" . $post_name . "'", 'ARRAY_A')) {
      return true;
    } else {
      return false;
    }
  }

  // Creates empty page if it does not yet exist
  function create_empty_page($title, $slug, $order) {
    $page = get_page_by_title($title);
    if(!isset($page->ID) && !the_slug_exists($slug)){
      wp_insert_post(array(
        'post_type' => 'page',
        'post_title' => $title,
        'post_content' => '',
        'post_status' => 'publish',
        'post_author' => 1,
        'menu_order' => $order,
        'post_slug' => $slug
      ));
    }
  }

  // Only attempt when activating theme
  if (isset($_GET['activated']) && is_admin()){

    // Remove sample page if it exists
    $sample_page = get_page_by_title('Sample Page');
    if(isset($sample_page->ID) && $sample_page->post_name == 'sample-page') {
      wp_delete_post($sample_page->ID);
    }

    create_empty_page('About', 'about', 0);
    create_empty_page('Contribute', 'contribute', 1);
    create_empty_page('High Scores', 'high-scores', 2);
    create_empty_page('Blog', 'blog', 3);
  }
?>
