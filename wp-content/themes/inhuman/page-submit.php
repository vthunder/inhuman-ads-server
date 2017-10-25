<?php get_header(); ?>
<?php wp_enqueue_style('post', get_template_directory_uri() . "/styles/page.css"); ?>
<?php inhuman_setup_js_vars(); ?>

<?php
  $action = !empty($_GET['action']) && ($_GET['action'] == 'edit' ||
                                        $_GET['action'] == 'delete') ?
            $_GET['action'] : 'submit';
  if (!empty($_GET['shot-id'] && !empty($_GET['shot-site']))) {
    $id = $_GET['shot-id'];
    $site = $_GET['shot-site'];
  } else {
    $error = true;
  }
?>

<div class="main">
  <?php if ($error): ?>

    <p>Oops, something went wrong. Please <a href="mailto:hello@inhumanads.com">contact us at hello@inhumanads.com</a> so we can fix it!</p>

  <?php else: ?>

    <script>
      var data = {
        action: 'inhuman_add_screenshot',
        shotId: "<?php echo $id; ?>",
        shotDomain: "<?php echo $site; ?>"
      };
			jQuery.post(jQuery('#php_data_ajax_url').val(), data, function(res) {
        if(res.success) {
          location = res.editUrl;
				} else {
          alert("Error posting shot. Try again later!");
				}
      }, 'json').fail(function(xhr, textStatus, e) {
				console.log(xhr.responseText);
			});
    </script>

  <?php endif; ?>
</div>

<?php get_footer(); ?>
