<?php
  $edit = isset($_GET['edit']);
  $isowner = is_user_logged_in() && $post->post_author == get_current_user_id();

  $brand = get_post_meta($post->ID, "inhuman_meta_ad_brand", true);
  $domain = get_post_meta($post->ID, "inhuman_meta_publisher_domain", true);

  $spam = get_post_meta($post->ID, "inhuman_flagged_status", true);
  $flag_count = get_post_meta($post->ID, "inhuman_flagged_unreviewed_count", true);
  if ($flag_count == '')
    $flag_count = 0;

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

<?php wp_enqueue_style('post', get_template_directory_uri() . "/styles/page/single-inhuman_screenshot.css"); ?>
<?php get_header('page'); ?>

<section class="title small">
  <div class="title-inner">
  </div>
</section>

<div class="content-wrapper">

  <div class="sidebars">
    <?php include('components/sidebar-submit-an-ad.php'); ?>
    <?php include('components/sidebar-latest-blog-posts.php'); ?>
  </div>

  <?php inhuman_setup_js_vars(); ?>
  <input id="post_id" type="hidden" value="<?php echo get_the_ID(); ?>">

  <section class="main-wrapper main-content">
    <div class="main">

      <?php if($edit && $isowner): ?>

        <div class="screenshot-edit">
          <div class="screenshot-card">
            <?php the_post_thumbnail('full'); ?>
          </div>
          <form id="screenshot-meta-form">
            <input type="hidden" name="post_id" value="<?php the_ID(); ?>" />
            <input class="meta-caption" name="caption" type="text" value="<?php the_title(); ?>" placeholder="Caption" />
            <input class="meta-brand" name="brand" type="text" value="<?php echo $brand; ?>" placeholder="Ad Brand" />
            <input class="meta-domain" name="domain" type="text" value="<?php echo $domain; ?>" placeholder="(unknown domain)" disabled="disabled" />
            <div class="meta-offensive-wrapper">
              <input class="meta-offensive" name="offensive" type="checkbox" />
              <label for="offensive">This screenshot might be offensive to some.</label>
            </div>
            <input id="post-screenshot" type="submit" class="button button-primary button-raised" value="Post to Inhuman Ads" />
            <input id="delete-screenshot" type="submit" class="button button-raised" value="Delete Post" />
          </form>
        </div>

      <?php else: ?>

        <h1 class="screenshot-caption"><?php the_title(); ?></h1>

        <?php
          $author_id = get_post_field('post_author', $post->ID);
          $author = get_user_by('id', $author_id);
        ?>
        <div class="screenshot-meta">
          <div class="left-meta">
            Spotted
            <?php if ($domain): ?>
              on: <a href="/?s=<?php echo $domain; ?>"><?php echo $domain; ?></a>
            <?php endif; ?>
            by <a href="/?s=<?php echo $author->display_name; ?>"><?php echo $author->display_name; ?></a>
          </div>

          <div class="share">
            <a class="facebook-share-button"
               data-fb-u="<?php echo urlencode(get_permalink()); ?>"
               data-fb-title="<?php echo urlencode(get_the_title()); ?>"
               href="#">
              <img src="<?php tpldir(); ?>/assets/btn-fb.svg">
            </a>
            <a class="twitter-share-button"
               data-twitter-status="<?php echo urlencode(get_the_title() . " " . get_permalink() . " #inhumanads"); ?>"
               href="#">
              <img src="<?php tpldir(); ?>/assets/btn-twitter.svg">
            </a>
            <a class="email-share-button"
               data-email="<?php echo urlencode(get_the_title() . "\n" . get_permalink()); ?>"
               href="#">
              <img src="<?php tpldir(); ?>/assets/btn-email.svg">
            </a>
          </div>
        </div>

        <div class="screenshot-card">
          <?php
            $class = "";
            if ($spam == "Confirmed" || $flag_count > 10) {
              echo '<p class="spam-shield">This post is potentially offensive or inappropriate.<br><br><a href="#">Load anyway</a></p>';
              $class = "hide";
            }
          ?>
          <div class="screenshot-image-plus-caption <?php echo $class; ?>">
            <?php the_post_thumbnail('full'); ?>
          </div>

          <div class="secondary-actions">
            <a id="report-link" href="#">
              <i class="fa fa-warning"></i><span class="action-txt">Report</span>
            </a>
            <?php if ($isowner): ?>
              &nbsp;
              <a href="?edit">
                <i class="fa fa-pencil"></i><span class="action-txt">Edit</span>
              </a>
            <?php endif; ?>
          </div>
        </div>

        <?php $prev_next = prevNextIds(get_the_ID(), ""); // FIXME: need to pass in $query_type here ?>
        <div class="bottom-meta">
          <?php if (!empty($prev_next['prev'])): ?>
            <a href="<?php echo get_permalink($prev_next['prev']); ?>" class="prev-screenshot">&laquo;prev</a>
          <?php endif; ?>

          <div class="like">
            <span class="like-text">This ad makes me feel: </span>

            <div class="like-buttons">
              <a class="like-emoji-link" data-emoji="funny" href="#">
                <img src="<?php tpldir(); ?>/assets/emojiicon-funny.svg">
                <span class="count-text funny"><?php echo $funny_likes; ?></span>
              </a>

              <a class="like-emoji-link" data-emoji="angry" href="#">
                <img src="<?php tpldir(); ?>/assets/emojiicon-angry.svg">
                <span class="count-text angry"><?php echo $angry_likes; ?></span>
              </a>

              <a class="like-emoji-link" data-emoji="sad" href="#">
                <img src="<?php tpldir(); ?>/assets/emojiicon-sad.svg">
                <span class="count-text sad"><?php echo $sad_likes; ?></span>
              </a>
            </div>
          </div>

          <?php if (!empty($prev_next['next'])): ?>
            <a href="<?php echo get_permalink($prev_next['next']); ?>" class="next-screenshot">next&raquo;</a>
          <?php endif; ?>
        </div>

        <?php if (!is_user_logged_in()): ?>
          <p>Have something to say about this ad? <a href="/login?orig_request=<?php echo urlencode("/screenshot/" . get_the_ID()); ?>">Log in</a> and leave a comment!</p>
        <?php endif; ?>
        <?php comments_template(); ?>

      <?php endif; ?>
    </div>
  </section>
</div>

<?php get_footer(); ?>
