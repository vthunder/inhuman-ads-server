<?php
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
<div class="card">
  <?php if ($spam_class == "hide"): ?>
    <p class="spam-shield">
      This post is potentially<br> offensive or inappropriate.<br><br>
      <a href="#">Load anyway</a>
    </p>
  <?php endif; ?>
  <div class="card-content <?php echo $spam_class; ?>">
    <a href="<?php the_permalink(); ?>">
      <div class="thumb" style="background-image: url(<?php echo $thumb_url; ?>);"></div>
    </a>
    <article>
      <h2><a href="<?php the_permalink(); ?>"><?php the_title() ?></a></h2>
      <?php
        $author_id = get_post_field('post_author', $post->ID);
        $author = get_user_by('id', $author_id);
      ?>
      <div class="author">By: <?php echo $author->display_name; ?></div>
    </article>
  </div>
</div>
