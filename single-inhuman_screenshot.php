<?php
  get_header();
  $meta = Inhuman_Meta::get_meta($post->ID);
?>

<img class="screenshot" src="<?php echo $meta['screenshot'] ?>" />

<?php comments_template(); ?>

<?php get_footer(); ?>
