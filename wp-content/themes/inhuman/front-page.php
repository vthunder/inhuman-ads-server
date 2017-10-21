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

<div class="main">
  <div class="container">

    <div class="filters-section">
      <a class="filter-emoji filter-latest <?php s("latest"); ?>" href="/">
        <i class="fa fa-3x fa-clock-o"></i>
        <span class="filter-text">Latest</span>
      </a>
      <a class="filter-emoji filter-mostviewed <?php s("popular"); ?>" href="/?e=popular">
        <img src="<?php tpldir(); ?>/assets/emojiicon-mostviewed.svg">
        <span class="filter-text">Popular</span>
      </a>
      <a class="filter-emoji filter-funny <?php s("funny"); ?>" href="/?e=funny">
        <img src="<?php tpldir(); ?>/assets/emojiicon-funny.svg">
        <span class="filter-text">Funny</span>
      </a>
      <a class="filter-emoji filter-angry <?php s("angry"); ?>" href="/?e=angry">
        <img src="<?php tpldir(); ?>/assets/emojiicon-angry.svg">
        <span class="filter-text">Angry</span>
      </a>
      <a class="filter-emoji filter-sad <?php s("sad"); ?>" href="/?e=sad">
        <img src="<?php tpldir(); ?>/assets/emojiicon-sad.svg">
        <span class="filter-text">Sad</span>
      </a>
    </div>

    <br><hr><br>

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
</div>

<div id="bottom-filler"></div>
<?php get_footer(); ?>
