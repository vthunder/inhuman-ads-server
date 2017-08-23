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
      <a class="filter-emoji filter-huh <?php s("huh"); ?>" href="/?e=huh">
        <img src="<?php tpldir(); ?>/assets/emojiicon-huh.svg">
        <span class="filter-text">Huh?</span>
      </a>
    </div>


    <hr>

    <div class="section-heading">
	    <h3><?php echo ucfirst($query_type); ?></h3>
    </div>
    <div class="all-posts grid">
	    <div class="grid-sizer"></div>
	    <div class="gutter-sizer"></div>
	    <?php
        $loop = new WP_Query(inhuman_query($query_type));
		    if ($loop->have_posts() ) : while ($loop->have_posts()) : $loop->the_post();
		    get_template_part('card', get_post_format());
		    endwhile; endif;
	    ?>
    </div>
  </div>
</div>

<div id="bottom-filler"></div>
