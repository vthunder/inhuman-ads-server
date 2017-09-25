<?php
  // Creates a page, or updates it if it already exists
  function create_or_update_page($title, $slug, $order, $content) {
    $page_by_title = get_page_by_title($title);
    $page_by_slug = get_page_by_path($page_path);
    $page = array(
      'post_type' => 'page',
      'post_title' => $title,
      'post_content' => $content,
      'post_status' => 'publish',
      'post_author' => 1,
      'menu_order' => $order,
      'post_slug' => $slug
    );
    if (isset($page_by_title->ID)) {
      $page['ID'] = $page_by_title->ID;
      wp_update_post($page);
    } elseif (isset($page_by_slug->ID)) {
      $page['ID'] = $page_by_slug->ID;
      wp_update_post($page);
    } else {
      wp_insert_post($page);
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
    create_or_update_page('About', 'about', 0, $page_content);

    require_once(plugin_dir_path(__FILE__) . '../default_pages/contribute.php');
    create_or_update_page('Contribute', 'contribute', 1, $page_content);

    create_or_update_page('High Scores', 'high-scores', 2, '');
    create_or_update_page('Blog', 'blog', 3, '');

    require_once(plugin_dir_path(__FILE__) . '../default_pages/subscribe.php');
    create_or_update_page('Subscribe', 'subscribe', 4, $page_content);
  }
?>
