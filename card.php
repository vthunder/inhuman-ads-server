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
    <a href="<?php the_permalink(); ?>">
      <?php the_post_thumbnail(); ?>
    </a>
    <div class="card-text">
      <a href="<?php the_permalink(); ?>">
        <h3 class="card-title"><?php the_title() ?></h3>
      </a>
      <p><?php the_excerpt() ?></p>
    </div>
  <?php elseif ("screenshot" == $meta['type']) : ?>
    <a href="<?php the_permalink(); ?>">
      <?php the_post_thumbnail(); ?>
      <div class="card-title"><?php the_title() ?></div>
    </a>
  <?php elseif ("contest" == $meta['type']) : ?>
    <a href="<?php the_permalink(); ?>">
      <?php the_post_thumbnail(); ?>
    </a>
    <?php the_content() ?>
  <?php elseif ("plain" == $meta['type'] or "background" == $meta['type']) : ?>
    <?php the_content() ?>
  <?php else : ?>
    Error: unknown post type!
  <?php endif; ?>
</div>
