<?php
  $type = get_post_meta($post->ID, '_inhuman_meta_key_type', true);
  $height = get_post_meta($post->ID, '_inhuman_meta_key_height', true);
  $width = get_post_meta($post->ID, '_inhuman_meta_key_width', true);

  $class = "card ";

  if ("background" == $type)
    $class .= "card-inset ";

  if ("short" == $height)
    $class .= "card-short ";
  if ("tall" == $height)
    $class .= "card-tall ";

  if ("wide" == $width)
    $class .= "card-wide ";
  if ("wide2" == $width)
    $class .= "card-wide2 ";

  $class = rtrim($class, " ");
?>
<div class="<?php echo $class ?>">
  <?php if ("" == $type) : ?>
    <h3 class="card-title"><?php the_title() ?></h3>
    <p><?php the_excerpt() ?></p>
  <?php elseif ("plain" == $type or "background" == $type) : ?>
    <?php the_content() ?>
  <?php else : ?>
    Error: unknown post type!
  <?php endif; ?>
</div>
