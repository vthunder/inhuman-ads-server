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
  <p>What are Inhuman Ads?</p>  
  <p>Lorem ipsum <a href="/contribute">Learn More&raquo;</a></p>

  <div class="latest-blog-posts-header">
    <span>Latest Blog Posts</span>
    <span><a href="/blog">See all&raquo;</a></span>
  </div>

  <p>Share Bad Ads</p>
  <button class="share-facebook"><i class="fa fa-facebook"></i> Share us on Facebook</button>
  <button class="share-twitter"><i class="fa fa-twitter"></i> Tweet about us</button>
</div>

<div class="main">
  <div class="all-posts grid">
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
