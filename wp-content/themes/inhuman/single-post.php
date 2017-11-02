<?php wp_enqueue_style('post', get_template_directory_uri() . "/styles/page/single-post.css"); ?>
<?php
  $fb_type = "article";
  $fb_image = get_the_post_thumbnail_url();
  include('header-page.php');
?>

<section class="title small">
  <div class="title-inner">
  </div>
</section>

<div class="content-wrapper">

  <div class="sidebars">
    <?php include('components/sidebar-submit-an-ad.php'); ?>
    <?php include('components/sidebar-latest-blog-posts.php'); ?>
    <?php include('components/sidebar-share-buttons.php'); ?>
  </div>

  <section class="main-wrapper main-content">
    <div class="main">
      <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
        <div class="article-body">
          <?php
            $url = get_permalink();
            $title = get_the_title();
            $fb = 'http://www.facebook.com/sharer/sharer.php?u=' . rawurlencode($url) .
                  '&title=' . rawurlencode($title);
            $tw = 'http://twitter.com/intent/tweet?status=' . rawurlencode("$title #inhumanads $url");
            $email = "mailto:Subject=" . rawurlencode("$title\n$url");
          ?>
          <div class="share">
            <a target="_blank" href="<?php echo $fb; ?>" class="facebook-share-button">
              <img src="<?php tpldir(); ?>/assets/btn-fb.svg">
            </a>
            <a target="_blank" href="<?php echo $tw; ?>" class="twitter-share-button">
              <img src="<?php tpldir(); ?>/assets/btn-twitter.svg">
            </a>
            <a target="_blank" href="<?php echo $email; ?>" class="email-share-button">
              <img src="<?php tpldir(); ?>/assets/btn-email.svg">
            </a>
          </div>
          <div class="post-header-image">
            <?php the_post_thumbnail(); ?>
            <div class="post-header-image-caption">
              <?php echo get_post(get_post_thumbnail_id())->post_excerpt; ?>
            </div>
          </div>
	        <h2 class="article-title"><?php the_title(); ?></h2>
	        <p class="article-meta"><?php the_date(); ?> by <a href="#"><?php the_author(); ?></a></p>
          <div class="article-content">
            <?php the_content(); ?>
          </div>
        </div>
      <?php endwhile; else : ?>
      <p><?php esc_html_e( 'Sorry, no posts matched your criteria.' ); ?></p>
      <?php endif; ?>
    </div>
  </section>

</div>

<?php get_footer(); ?>
