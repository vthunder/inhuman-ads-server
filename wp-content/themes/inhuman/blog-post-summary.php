<?php
  $thumb_url = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'medium');
  if ($thumb_url)
    $thumb_url = $thumb_url[0];
?>
<div class="blog-post-summary">
  <a class="thumb" href="<?php the_permalink(); ?>">
    <div style="background-image: url(<?php echo $thumb_url; ?>);"></div>
  </a>
  <a class="text" href="<?php the_permalink(); ?>">
    <h3><?php the_title(); ?></h3>
  </a>
</div>
