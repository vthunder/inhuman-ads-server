<?php

  // Check that the nonce is valid, user is logged in
  if (isset($_POST['screenshot_upload_nonce'])
      && is_user_logged_in()
	    && wp_verify_nonce($_POST['screenshot_upload_nonce'], 'screenshot_upload')) {

	  // These files need to be included as dependencies when on the front end.
	  require_once( ABSPATH . 'wp-admin/includes/image.php' );
	  require_once( ABSPATH . 'wp-admin/includes/file.php' );
	  require_once( ABSPATH . 'wp-admin/includes/media.php' );

    // create new screenshot post
    $post_id = wp_insert_post(array(
      'post_title'    => '',
      'post_content'  => '',
      'post_status'   => 'publish',
      'post_author'   => get_current_user_id(),
      'post_type' => 'inhuman_screenshot',
      'meta_input' => array(
        'inhuman_meta_type' => 'screenshot',
        'inhuman_meta_status' => 'draft'
      )
    ));

	  $attachment_id = media_handle_upload('screenshot_upload', $post_id);
    set_post_thumbnail($post_id, $attachment_id);

	  if (is_wp_error($attachment_id)) {
      echo "Error uploading image: " . $attachment_id;
	  } else {
      echo '<script>location = "/screenshot/' . $post_id . '?edit";</script>';
	  }
  }
?>

<?php wp_enqueue_style('post', get_template_directory_uri() . "/styles/page/page-contribute.css"); ?>
<?php get_header('page'); ?>

<section class="title small">
  <div class="title-inner">
    <img class="clouds cloud-left" src="<?php tpldir(); ?>/assets/clouds-left.png">
    <img class="logo" src="<?php tpldir(); ?>/assets/submit-an-ad.png">
    <img class="clouds cloud-right" src="<?php tpldir(); ?>/assets/clouds-right.png">
  </div>
</section>

<div class="content-wrapper">

  <div class="sidebars">
    <?php include('components/sidebar-faq.php'); ?>
  </div>
  
  <section class="main-wrapper main-content">
    <div class="main">
      <img class="landing1" src="<?php tpldir(); ?>/assets/landing-screenshots_image_01_57.svg">
      <h2>Ready to call out some bad ads with us?</h2>

      <?php if (strlen(strstr($_SERVER['HTTP_USER_AGENT'], "Firefox")) > 0): ?>
        <p>It’s easy submitting inhuman ads with Firefox Screenshots!</p>

        <p>Firefox Screenshots is a new feature to take, download, collect
          and share screenshots.</p>

        <p>To submit a bad ad, you will need to click on the button below
          to install the Inhuman Ads add-on (no restart required):</p>

        <p><a id="install_button" href="https://addons.mozilla.org/firefox/downloads/latest/inhuman-ads/addon-841180-latest.xpi?src=dp-btn-primary" class="button button-primary">Activate Inhuman Ads</a></p>

        <p>Once the add-on is installed, simply take a screenshot and
          click the “Send to Inhuman Ads” button!</p>

        <img class="landing2" src="<?php tpldir(); ?>/assets/send-to-inhuman-ads.png">
      <?php else: ?>
        <p>It’s easy submitting inhuman ads with Firefox Screenshots!</p>

        <p>Firefox Screenshots is a new feature to take, download, collect
          and share screenshots. Get Firefox to try it out:</p>

        <p><a id="install_button" href="https://www.mozilla.org/firefox/new/?scene=2&utm_source=inhumanads.com&utm_medium=referral&utm_campaign=non-fx-button#download-fx" class="button button-primary">Only with Firefox — Get Firefox Now!</a></p>
      <?php endif; ?>

      <p>Don’t have Firefox Screenshots?</p>

      <p>No problem, but you should really give it a try! If you’ve
        already got a screenshot of an inhuman ad you would like to
        submit, click the button below:</p>


      <p>
        <form id="image_upload" method="post" action="#" enctype="multipart/form-data">
          <div class="upload-button button button-primary">
            Select file on your computer...
	          <input type="file" class="upload" name="screenshot_upload" id="screenshot_upload" value="" />
          </div><br>
	        <?php wp_nonce_field('screenshot_upload', 'screenshot_upload_nonce'); ?>
        </form>
      </p>

      <script>
        <?php if (is_user_logged_in()): ?>
        jQuery('#screenshot_upload').change(function() {
          jQuery('#image_upload').submit();
        });
        <?php else: ?>
        jQuery('#screenshot_upload').click(function(e) {
          e.preventDefault();
          location = "/login?orig_request=" + encodeURIComponent("/contribute");
        });
        <?php endif; ?>
      </script>

      <p>Now, go forth and browse, find the worst ads! Good luck with your
        newfound superpower — use it wisely.</p>
    </div>
  </section>

</div>

<?php get_footer(); ?>
