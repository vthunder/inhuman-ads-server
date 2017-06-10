<?php
  $meta = Inhuman_Meta::get_meta($post->ID);
  $class = "card ";

  if ("background" == $meta['type'])
    $class .= "card-inset ";

  if ("short" == $meta['height'])
    $class .= "card-short ";
  if ("tall" == $meta['height'])
    $class .= "card-tall ";

  if ("wide" == $meta['width'])
    $class .= "card-wide ";
  if ("wide2" == $meta['width'])
    $class .= "card-wide2 ";

  $class = rtrim($class, " ");
?>
<div class="<?php echo $class ?>">
  <?php if ("" == $meta['type']) : ?>
    <h3 class="card-title"><a href="<?php the_permalink(); ?>"><?php the_title() ?></a></h3>
    <p><?php the_excerpt() ?></p>
  <?php elseif ("screenshot" == $meta['type']) : ?>
    <img class="screenshot_thumb" src="<?php echo $meta['screenshot'] ?>" />
    <?php the_content() ?>
  <?php elseif ("plain" == $meta['type'] or "background" == $meta['type']) : ?>
    <?php the_content() ?>
  <?php else : ?>
    Error: unknown post type!
  <?php endif; ?>
</div>
