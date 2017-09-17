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

  // Creates a page if it does not yet exist
  function create_page($title, $slug, $order, $content) {
    $page = get_page_by_title($title);
    if(!isset($page->ID) && !the_slug_exists($slug)){
      wp_insert_post(array(
        'post_type' => 'page',
        'post_title' => $title,
        'post_content' => $content,
        'post_status' => 'publish',
        'post_author' => 1,
        'menu_order' => $order,
        'post_slug' => $slug
      ));
    }
  }

  function read_file($filename) {
    $handle = fopen($filename, "r");
    $contents = fread($handle, filesize($filename));
    fclose($handle);
    return $contents;
  }

  // Only attempt when activating theme
  if (isset($_GET['activated']) && is_admin()){

    // Remove sample page if it exists
    $sample_page = get_page_by_title('Sample Page');
    if(isset($sample_page->ID) && $sample_page->post_name == 'sample-page') {
      wp_delete_post($sample_page->ID);
    }

    // NOTE: after much fiddling (and failing) with the php
    // file-reading functions, I've settled on this hack: php includes
    // under default_pages/ are expected to define a variable called
    // '$page_content'. Each include re-defines the same variable.

    require_once(plugin_dir_path(__FILE__) . '../default_pages/about.php');
    create_page('About', 'about', 0, $page_content);

    require_once(plugin_dir_path(__FILE__) . '../default_pages/contribute.php');
    create_page('Contribute', 'contribute', 1, $page_content);

    create_page('High Scores', 'high-scores', 2, '');
    create_page('Blog', 'blog', 3, '');

    require_once(plugin_dir_path(__FILE__) . '../default_pages/subscribe.php');
    create_page('Subscribe', 'subscribe', 4, $page_content);
  }
?>
