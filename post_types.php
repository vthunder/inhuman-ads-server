<?php

  //
  // Screenshot Post Type
  //
  function create_screenshot_post_type() {
    register_post_type('inhuman_screenshot',
		                   array(
			                   'labels' => array(
			                     'name' => __( 'Screenshots' ),
			                     'singular_name' => __( 'Screenshot' ),
			                   ),
			                   'public' => true,
			                   'has_archive' => true,
			                   'supports' => array(
			                     'title',
			                     'thumbnail',
                           'comments',
                           //			       'custom-fields'
			                   ),
			                   'taxonomies'   => array(
			                     'post_tag',
			                     'category',
			                   )
                       ));
  }
  add_action( 'init', 'create_screenshot_post_type' );

  //
  // Contest Post Type
  //
  function create_contest_post_type() {
    register_post_type('inhuman_contest',
		                   array(
			                   'labels' => array(
			                     'name' => __( 'Contests' ),
			                     'singular_name' => __( 'Contest' ),
			                   ),
			                   'public' => true,
			                   'has_archive' => true,
			                   'supports' => array(
			                     'title',
			                     'thumbnail',
                           'comments',
                           //			       'custom-fields'
			                   ),
			                   'taxonomies'   => array(
			                     'post_tag',
			                     'category',
			                   )
                       ));
  }
  add_action( 'init', 'create_contest_post_type' );

?>
