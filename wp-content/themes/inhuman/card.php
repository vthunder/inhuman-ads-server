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

  $spam = get_post_meta($post->ID, "inhuman_flagged_status", true);
  $flag_count = get_post_meta($post->ID, "inhuman_flagged_unreviewed_count", true);
  if ($flag_count == '')
    $flag_count = 0;
  $spam_class = "";
  if ($spam == "Confirmed" || $flag_count > 10)
    $spam_class = "hide";

  $thumb_url = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID),
                                           'medium_large');
  if ($thumb_url)
    $thumb_url = $thumb_url[0];
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
    <?php if ($spam_class == "hide"): ?>
      <p class="spam-shield">
        This post is potentially offensive or inappropriate.<br><br>
        <a href="#">Load anyway</a>
      </p>
    <?php endif; ?>
    <div class="card-content <?php echo $spam_class; ?>">
      <article>
        <h2><a href="<?php the_permalink(); ?>"><?php the_title() ?></a></h2>
        <?php
          $author_id = get_post_field('post_author', $post->ID);
          $author = get_user_by('id', $author_id);
        ?>
        <div class="author">By: <?php echo $author->display_name; ?></div>
      </article>
      <a href="<?php the_permalink(); ?>">
        <div class="thumb" style="background-image: url(<?php echo $thumb_url; ?>);"></div>
      </a>
    </div>


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
