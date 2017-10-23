<?php
  $author_id = get_post_field('post_author', $post->ID);
  $author = get_user_by('id', $author_id);

  $domain = get_post_meta($post->ID, "inhuman_meta_publisher_domain", true);

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

  $likes = get_post_meta(get_the_ID(), "inhuman_meta_total_like_count", true);
  if (!$likes)
    $likes = 0;
  $funny_likes = get_post_meta(get_the_ID(), "inhuman_meta_like_funny_count", true);
  if (!$funny_likes)
    $funny_likes = 0;
  $angry_likes = get_post_meta(get_the_ID(), "inhuman_meta_like_angry_count", true);
  if (!$angry_likes)
    $angry_likes = 0;
  $sad_likes = get_post_meta(get_the_ID(), "inhuman_meta_like_sad_count", true);
  if (!$sad_likes)
    $sad_likes = 0;
?>
<div class="card">
  <?php if ($spam_class == "hide"): ?>
    <p class="spam-shield">
      This post is potentially<br> offensive or inappropriate.<br><br>
      <a href="#">Load anyway</a>
    </p>
  <?php endif; ?>
  <div class="card-content <?php echo $spam_class; ?>">
    <?php if ($card_num): ?>
      <div class="card-num">
        <div class="number"><?php echo $card_num; ?></div>
      </div>
    <?php endif; ?>
    <a href="<?php the_permalink(); ?>">
      <div class="thumb" style="background-image: url(<?php echo $thumb_url; ?>);"></div>
    </a>
    <article>
      <div class="details">
        <a href="/?s=<?php echo urlencode($domain); ?>" class="domain"><?php echo $domain; ?></a>
        <span class="author">By: <a href="/?s=<?php echo urlencode($author->display_name); ?>"><?php echo $author->display_name; ?></a>
        </span>
      </div>
      <h3><a href="<?php the_permalink(); ?>"><?php the_title() ?></a></h3>
      <div class="bottom-details">
        <div class="likes">
          <div class="funny">
            <img src="<?php tpldir(); ?>/assets/emojiicon-funny.svg">
            <span class="count-text"><?php echo $funny_likes; ?></span>
          </div>
          <div class="angry">
            <img src="<?php tpldir(); ?>/assets/emojiicon-angry.svg">
            <span class="count-text"><?php echo $angry_likes; ?></span>
          </div>
          <div class="sad">
            <img src="<?php tpldir(); ?>/assets/emojiicon-sad.svg">
            <span class="count-text"><?php echo $sad_likes; ?></span>
          </div>
        </div>
        <div class="posted-time"><?php echo human_time_diff(get_the_time('U'), current_time('timestamp')); ?> ago</div>
      </div>
    </article>
  </div>
</div>
