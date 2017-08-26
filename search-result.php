<?php
  $brand = get_post_meta($post->ID, "inhuman_meta_ad_brand", true);
  $domain = get_post_meta($post->ID, "inhuman_meta_publisher_domain", true);
  $spam = get_post_meta($post->ID, "inhuman_flagged_status", true);
  $flag_count = get_post_meta($post->ID, "inhuman_flagged_unreviewed_count", true);
  if ($flag_count == '')
    $flag_count = 0;
  $spam_class = "";
  if ($spam == "Confirmed" || $flag_count > 10)
    $spam_class = "hide";
?>
<li>
  <?php if ($spam_class == "hide"): ?>
    <p class="spam-shield">This post is potentially offensive or inappropriate.<br><br><a href="#">Load anyway</a></p>
  <?php endif; ?>
  <a class="<?php echo $spam_class; ?>" href="<?php the_permalink(); ?>">
    <?php the_post_thumbnail("thumbnail"); ?>
    <div class="meta">
      <div class="caption"><?php the_title() ?></div>
      <div class="brand-domain">
        <span class="brand">Brand: <?php echo $brand; ?></span>
        <span class="domain">Domain: <?php echo $domain; ?></span>
      </div>
    </div>
  </a>
</li>
