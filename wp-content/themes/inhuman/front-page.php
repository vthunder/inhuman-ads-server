<?php
  $query_type = get_query_var('e', 'latest');
  function s($emoji) {
    global $query_type;
    if ($emoji == $query_type)
      echo "selected";
  }
?>
<?php get_header(); ?>
<?php get_sidebar(); ?>
<?php inhuman_setup_js_vars(); ?>

<div class="left-sidebar">
  <a href="/contribute">
    <img class="submit-an-ad" src="<?php tpldir(); ?>/assets/submit-an-ad.png">
  </a>
  <h3>What is Inhuman Ads?</h3>
  <p>Inhuman Ads is a space for humans to point out the mess made by automated platforms and advertiser dollars, so we can one day clean it up. It's a forum for you to share the funny, frustrating, and infuriating ad placements you find in your online adventures. <a href="/about">Learn More&raquo;</a></p>

  <div class="latest-blog-posts-header">
    <h3>Latest Blog Posts</h3>
    <span><a href="/blog">See all&raquo;</a></span>
  </div>
  <div class="latest-blog-posts">
	  <?php
      $loop = new WP_Query(inhuman_query('blog', null, 10));
		  if ($loop->have_posts() ) : while ($loop->have_posts()) : $loop->the_post();
		  get_template_part('blog-post-summary', get_post_format());
      $shown_ids[] = get_the_ID();
		  endwhile; endif;
	  ?>
  </div>

  <h3>Share Bad Ads</h3>
  <div class="share-buttons">
    <button class="share-facebook"><i class="fa fa-facebook"></i> Share us on Facebook</button>
    <button class="share-twitter"><i class="fa fa-twitter"></i> Tweet about us</button>
  </div>
</div>

<div class="main">
  <h3>Top 10 Most Inhuman Ads</h3>
  <div class="top-posts">
    <button class="arrow arrow-left"><i class="fa fa-caret-left"></i></button>
    <div class="top-posts-viewport">
      <div class="top-posts-list">
	      <?php
          $loop = new WP_Query(inhuman_query('popular', null, 10));
		      if ($loop->have_posts() ) : while ($loop->have_posts()) : $loop->the_post();
		      get_template_part('card', get_post_format());
          $shown_ids[] = get_the_ID();
		      endwhile; endif;
	      ?>
      </div>
    </div>
    <button class="arrow arrow-right"><i class="fa fa-caret-right"></i></button>
  </div>

  <h3>More Bad Ads</h3>
  <div class="grid">
	  <?php
      $loop = new WP_Query(inhuman_query($query_type));
		  if ($loop->have_posts() ) : while ($loop->have_posts()) : $loop->the_post();
		  get_template_part('card', get_post_format());
      $shown_ids[] = get_the_ID();
		  endwhile; endif;
	  ?>
  </div>
  <div class="the-end">You've reached the end!<br>
    Maybe you can <a href="/contribute">contribute a new post</a>?
  </div>
</div>

<div id="bottom-filler"></div>
<?php get_footer(); ?>
