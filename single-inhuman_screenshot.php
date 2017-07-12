<?php
  get_header('post');
  wp_enqueue_style('post', get_template_directory_uri() . "/styles/post.css");
  $meta = Inhuman_Meta::get_meta($post->ID);
  $edit = isset($_GET['edit']);
  $isowner = is_user_logged_in() && $post->post_author == get_current_user_id();
?>

<div class="content">
  <?php if($edit && $isowner): ?>
    <div class="screenshot-edit">
      <div class="screenshot-card">
        <img class="screenshot" src="<?php echo $meta['screenshot'] ?>" />
      </div>
      <form class="screenshot-meta-form">
        <input class="meta-caption" name="caption" type="text" placeholder="Caption" />
        <input class="meta-brand" name="brand" type="text" placeholder="Brand" />
        <input class="meta-domain" name="domain" type="text" placeholder="www.domain.com" />
        <div class="meta-offensive-wrapper">
          <input class="meta-offensive" name="offensive" type="checkbox" />
          <label for="offensive">This screenshot might be offensive to some.</label>
        </div>
      </form>
      <a class="button button-primary button-raised" href="#">Post</a>
    </div>
    
  <?php else: ?>
    <div class="screenshot-card">
      <img class="screenshot" src="<?php echo $meta['screenshot'] ?>" />
      <div class="screenshot-meta">
        <span class="screenshot-caption">Caption goes here</span>
        <span class="screenshot-actions">
          <a class="like" href="#"><i class="fa fa-smile-o fa-lg"></i></a>
          <a class="share" href="#"><i class="fa fa-share-square-o fa-lg"></i></a>
        </span>
      </div>
      <div class="screenshot-meta-2">
        <span class="screenshot-brand">Brand</span>
        <span class="screenshot-domain">Spotted on: www.domain.com</span>
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
