<?php
  get_header();
  wp_enqueue_style('post', get_template_directory_uri() . "/styles/post.css");

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
?>
<?php get_sidebar(); ?>
<?php inhuman_setup_js_vars(); ?>

<input id="post_id" type="hidden" value="<?php echo get_the_ID(); ?>">

<div class="content">
  <?php if($edit && $isowner): ?>
    <?php if (get_user_meta(get_current_user_id(), "inhuman_user_complete", true)): ?>
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
      <p>Looks like you're new around here!</p>
      <p>Pick a username that we'll use on the site to identify
        you. It will be visible on screenshot pages, our high scores
        boards, etc. If you provide an email, we'll use it for account
        recovery and to send you site updates and responses to your
        comments.</p>
      <ul>
        <li>Your screenshot posts will be available at Inhuman Ads indefinitely unless you delete them.</li>
        <li>Use of Inhuman Ads is subject to these <a href="https://www.mozilla.org/en-US/about/legal/terms/mozilla/">terms and conditions</a>.</li>
        <li>Read our <a href="https://www.mozilla.org/en-US/privacy/websites/">Privacy Notice</a></li>
      </ul>
      <div id="user-setup">
        <span id="user-setup-error"></span>
        <form id="user-setup-create">
          <div>
            <label for="name">Name:</label>
            <input type="text" name="name" value="<?php echo user()->display_name; ?>">
          </div>
          <div>
            <label for="email">Email:</label>
            <input type="text" name="email" placeholder="(optional)">
          </div>
          <input type="button" class="button button-primary" value="Submit">
        </form>
        <form id="user-setup-existing">
          <!--
               <div>
               <label for="email">Email:</label>
               <input type="text" name="email" placeholder="">
               </div>
               <input type="button" class="button button-primary" value="Submit"> -->
          This feature isn't implemented yet.<br>Create a new username on this browser/device.
        </form>
        <a id="existing-account-link" href="#">Use existing account</a>
        <a id="create-account-link" href="#">Create account</a>
      </div>
    <?php endif; ?>

  <?php else: ?>
    <div class="screenshot-card">
      <?php $prev_next = prevNextIds(get_the_ID(), ""); // FIXME: need to pass in $query_type here ?>
      <?php if (!empty($prev_next['prev'])): ?>
        <a href="<?php echo get_permalink($prev_next['prev']); ?>" class="fa fa-2x fa-chevron-circle-left prev-screenshot"></a>
      <?php endif; ?>
      <?php if (!empty($prev_next['next'])): ?>
        <a href="<?php echo get_permalink($prev_next['next']); ?>" class="fa fa-2x fa-chevron-circle-right next-screenshot"></a>
      <?php endif; ?>
      <?php
        $class = "";
        if ($spam == "Confirmed" || $flag_count > 10) {
          echo '<p class="spam-shield">This post is potentially offensive or inappropriate.<br><br><a href="#">Load anyway</a></p>';
          $class = "hide";
        }
      ?>
      <div class="screenshot-image-plus-caption <?php echo $class; ?>">
        <?php the_post_thumbnail('full'); ?>
        <div class="screenshot-meta">
          <span class="screenshot-caption"><?php the_title(); ?></span>
          <span class="screenshot-actions">
            <span class="like">
              <a class="like-link" href="#"><i class="fa fa-smile-o fa-lg"></i></a>
              <div class="like-box-wrapper">
                <div class="like-box">
                  <a class="like-emoji-link" data-emoji="funny" href="#">
                    <img src="<?php tpldir(); ?>/assets/emojiicon-funny.svg">
                  </a>
                  <a class="like-emoji-link" data-emoji="angry" href="#">
                    <img src="<?php tpldir(); ?>/assets/emojiicon-angry.svg">
                  </a>
                  <a class="like-emoji-link" data-emoji="sad" href="#">
                    <img src="<?php tpldir(); ?>/assets/emojiicon-sad.svg">
                  </a>
                  <!--
                  <a class="like-emoji-link" data-emoji="huh" href="#">
                    <img src="<?php tpldir(); ?>/assets/emojiicon-huh.svg">
                  </a>
                  -->
                </div>
              </div>
            </span>

            <span class="share">
              <a class="share-link" href="#"><i class="fa fa-share-square-o fa-lg"></i></a>
              <div class="share-box-wrapper">
                <div class="share-box">
                  <a class="twitter-share-button"
                     href="http://twitter.com/intent/tweet?status=<?php echo urlencode(get_the_title() . " " . get_permalink() . " #inhumanads"); ?>">
                    <i class="fa fa-twitter-square fa-3x"></i></a>
                  <a class="facebook-share-button"
                     href="http://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode(get_permalink()); ?>&title=<?php echo urlencode(get_the_title()); ?>">
                    <i class="fa fa-facebook-square fa-3x"></i></a>
                  <a class="email-share-button"
                     href="mailto:?Subject=<?php echo urlencode(get_the_title() . "\n" . get_permalink()); ?>">
                    <i class="fa fa-envelope-square fa-3x"></i></a>
                </div>
              </div>
            </span>
          </span>
          <div class="like-emoji-count-box">
            <img src="<?php tpldir(); ?>/assets/emojiicon-funny.svg">
            <span class="count-text"><?php echo $likes; ?></span>
          </div>
        </div>
      </div>
      <div class="screenshot-meta-2">
        <span class="screenshot-brand">Brand: <a href="/?s=<?php echo $brand; ?>"><?php echo $brand; ?></a></span>
        <?php
          $author_id = get_post_field('post_author', $post->ID);
          $author = get_user_by('id', $author_id);
        ?>
        <span class="screenshot-domain">Spotted on: <a href="/?s=<?php echo $domain; ?>"><?php echo $domain; ?></a> by <?php echo $author->display_name; ?></span>
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

    <hr>

    <?php comments_template(); ?>
  <?php endif; ?>
</div>

<?php get_footer('post'); ?>
