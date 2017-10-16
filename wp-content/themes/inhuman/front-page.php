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

    <!--
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
    -->

    <div class="section-heading">
      <img src="<?php tpldir(); ?>/assets/emojiicon-mostviewed.svg">
      <h3 class="heading-text">Most Popular</h3>
    </div>
    <div class="popular-posts grid">
	    <div class="grid-sizer"></div>
	    <div class="gutter-sizer"></div>
	    <?php
        $loop = new WP_Query(inhuman_query("popular", null, 4, $shown_ids));
		    if ($loop->have_posts() ) : while ($loop->have_posts()) : $loop->the_post();
		    get_template_part('card', get_post_format());
		    endwhile; endif;
	    ?>
    </div>

    <?php
      $shown_ids = [];
      $loop = new WP_Query(inhuman_query("funny", null, 4, $shown_ids));
      if ($loop->have_posts()):
    ?>
      <div class="section-heading">
        <hr>
        <img src="<?php tpldir(); ?>/assets/emojiicon-funny.svg">
        <h3 class="heading-text">Top "Funny"</h3>
      </div>
      <div class="funny-posts grid">
	      <div class="grid-sizer"></div>
	      <div class="gutter-sizer"></div>
	      <?php
		      while ($loop->have_posts()) : $loop->the_post();
		      get_template_part('card', get_post_format());
          $shown_ids[] = get_the_ID();
		      endwhile;
	      ?>
      </div>
    <?php endif; ?>

    <?php
      $loop = new WP_Query(inhuman_query("angry", null, 4, $shown_ids));
      if ($loop->have_posts()):
    ?>
      <div class="section-heading">
        <hr>
        <img src="<?php tpldir(); ?>/assets/emojiicon-angry.svg">
        <h3 class="heading-text">Top "Angry"</h3>
      </div>
      <div class="angry-posts grid">
	      <div class="grid-sizer"></div>
	      <div class="gutter-sizer"></div>
	      <?php
		      while ($loop->have_posts()) : $loop->the_post();
		      get_template_part('card', get_post_format());
          $shown_ids[] = get_the_ID();
		      endwhile;
	      ?>
      </div>
    <?php endif; ?>
    

    <?php
      $loop = new WP_Query(inhuman_query("sad", null, 4, $shown_ids));
      if ($loop->have_posts()):
    ?>
      <div class="section-heading">
        <hr>
        <img src="<?php tpldir(); ?>/assets/emojiicon-sad.svg">
        <h3 class="heading-text">Top "Sad"</h3>
      </div>
      <div class="sad-posts grid">
	      <div class="grid-sizer"></div>
	      <div class="gutter-sizer"></div>
	      <?php
		      while ($loop->have_posts()) : $loop->the_post();
		      get_template_part('card', get_post_format());
          $shown_ids[] = get_the_ID();
		      endwhile;
	      ?>
      </div>
    <?php endif; ?>

    <div class="section-heading">
      <hr>
      <i class="fa fa-3x fa-clock-o"></i>
      <h3 class="heading-text">Latest</h3>
    </div>
    <div class="all-posts grid">
	    <div class="grid-sizer"></div>
	    <div class="gutter-sizer"></div>
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
