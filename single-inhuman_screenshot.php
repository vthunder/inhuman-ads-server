<?php
  get_header('post');
  wp_enqueue_style('post', get_template_directory_uri() . "/styles/post.css");
  $meta = get_post_meta(get_the_ID());
  $edit = isset($_GET['edit']);
  $isowner = is_user_logged_in() && $post->post_author == get_current_user_id();
?>

<div class="content">
  <?php if($edit && $isowner): ?>
    <div class="screenshot-edit">
      <div class="screenshot-card">
        <?php the_post_thumbnail('full'); ?>
      </div>
      <form id="screenshot-meta-form">
        <input type="hidden" name="post_id" value="<?php the_ID(); ?>" />
        <input class="meta-caption" name="caption" type="text" value="<?php the_title(); ?>" placeholder="Caption" />
        <input class="meta-brand" name="brand" type="text" value="<?php echo $meta['inhuman_meta_ad_brand'][0]; ?>" placeholder="Ad Brand" />
        <input class="meta-domain" name="domain" type="text" value="<?php echo $meta['inhuman_meta_publisher_domain'][0]; ?>" placeholder="(unknown domain)" disabled="disabled" />
        <div class="meta-offensive-wrapper">
          <input class="meta-offensive" name="offensive" type="checkbox" />
          <label for="offensive">This screenshot might be offensive to some.</label>
        </div>
        <input type="submit" class="button button-primary button-raised" value="Post" />
      </form>
    </div>
    
  <?php else: ?>
    <div class="screenshot-card">
      <?php the_post_thumbnail('full'); ?>
      <div class="screenshot-meta">
        <span class="screenshot-caption"><?php the_title(); ?></span>
        <span class="screenshot-actions">
          <a class="like" href="#"><i class="fa fa-smile-o fa-lg"></i></a>
          <a class="share" href="#"><i class="fa fa-share-square-o fa-lg"></i></a>
        </span>
      </div>
      <div class="screenshot-meta-2">
        <span class="screenshot-brand">Brand: <?php echo $meta["inhuman_meta_ad_brand"][0]; ?></span>
        <span class="screenshot-domain">Spotted on: <?php echo $meta["inhuman_meta_publisher_domain"][0]; ?></span>
      </div>
      <a class="offensive" href="#">
        <i class="fa fa-warning"></i>
        <span class="report-txt">Report</span>
      </a>
    </div>

    <hr>

    <?php comments_template(); ?>
  <?php endif; ?>
</div>

<?php get_footer('post'); ?>
