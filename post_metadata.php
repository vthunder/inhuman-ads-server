<?php

  //
  // Custom metadata
  //
  abstract class Inhuman_Meta {

    public static $keys = array(
      'type', 'status', 'featured', 'sort', 'width', 'height',
      'shot_url', 'shot_id', 'shot_domain',
      'ad_brand', 'publisher_domain', 'flagged_count', 'offensive',
      'like_funny_count', 'like_angry_count', 'like_sad_count', 'like_huh_count', 'total_like_count'
    );

    public static function get_meta($post_id) {
      $meta = array();
      foreach (self::$keys as $key) {
        $meta[$key] = get_post_meta($post_id, 'inhuman_meta_' . $key, true);
      }
      return $meta;
    }

    public static function add() {
      $screens = ['post', 'inhuman_screenshot', 'inhuman_contest'];
      foreach ($screens as $screen) {
        add_meta_box(
          'inhuman_box_id',          // Unique ID
          'Inhuman Ads Options', // Box title
          [self::class, 'html'],   // Content callback, must be of type callable
          $screen                  // Post type
        );
      }
    }
    
    public static function save($post_id) {
	    // verify nonce
	    if ( !wp_verify_nonce( $_POST['inhuman_meta_nonce'], basename(__FILE__) ) ) {
	      return $post_id; 
	    }
	    // check autosave
	    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
	      return $post_id;
	    }
	    // check permissions
	    if ( 'page' === $_POST['post_type'] ) {
	      if ( !current_user_can( 'edit_page', $post_id ) ) {
		      return $post_id;
	      } elseif ( !current_user_can( 'edit_post', $post_id ) ) {
		      return $post_id;
	      }  
	    }

      $new = $_POST['inhuman_meta'];
      $old = self::get_meta($post_id);

      foreach (self::$keys as $key) {
        if ($new[$key] && $new[$key] !== $old[$key]) {
          update_post_meta($post_id, 'inhuman_meta_' . $key, $new[$key]);
        } elseif (!$new[$key] && $old[$key]) {
          delete_post_meta($post_id, 'inhuman_meta_' . $key, $old[$key]);
        }
      }
    }
    
    public static function html($post) {
	    $meta = Inhuman_Meta::get_meta($post->ID); ?>

  <div class="heading"><b>Inappropriate content/spam</b></div>
  <?php
    $flagged_status = get_post_meta($post->ID, "inhuman_flagged_status", true);
    if ($flagged_status == '')
      $flagged_status = "No flags";
  ?>
  Current setting: <?php echo $flagged_status; ?>
	<ul class="bullet-links">
    <li><a class="inhuman_flag_confirm" href="<?php echo admin_url("admin-post.php?post_type=inhuman_screenshot&action=inhuman_flag_confirm&post=$post->ID"); ?>">Spam</a></li>
	  <li><a class="inhuman_flag_clear" href="<?php echo admin_url("admin-post.php?post_type=inhuman_screenshot&action=inhuman_flag_clear&post=$post->ID"); ?>">Not spam</a></li>
  </ul>

<?php }
  }
  add_action('add_meta_boxes', ['Inhuman_Meta', 'add']);
  add_action('save_post', ['Inhuman_Meta', 'save']);

?>
