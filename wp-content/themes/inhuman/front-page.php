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
