<?php get_header(); ?>

<?php get_sidebar(); ?>

<div class="container">

  <div class="filters-section">
    <a class="filter-emoji filter-mostviewed selected" href="#">
      <img src="<?php tpldir(); ?>/assets/emojiicon-mostviewed.svg">
      <span class="filter-text">Popular</span>
    </a>
    <a class="filter-emoji filter-funny" href="#">
      <img src="<?php tpldir(); ?>/assets/emojiicon-funny.svg">
      <span class="filter-text">Funny</span>
    </a>
    <a class="filter-emoji filter-angry" href="#">
      <img src="<?php tpldir(); ?>/assets/emojiicon-angry.svg">
      <span class="filter-text">Angry</span>
    </a>
    <a class="filter-emoji filter-sad" href="#">
      <img src="<?php tpldir(); ?>/assets/emojiicon-sad.svg">
      <span class="filter-text">Sad</span>
    </a>
    <a class="filter-emoji filter-huh" href="#">
      <img src="<?php tpldir(); ?>/assets/emojiicon-huh.svg">
      <span class="filter-text">Huh?</span>
    </a>
  </div>


  <hr>

  <div class="section-heading">
    <h3>Latest</h3>
  </div>
  <div class="all-posts grid">
	  <div class="grid-sizer"></div>
	  <div class="gutter-sizer"></div>
	  <?php 
      $loop = new WP_Query($inhuman_posts_query);
		  if ( $loop->have_posts() ) : while ( $loop->have_posts() ) : $loop->the_post();
		  get_template_part( 'card', get_post_format() );
		  endwhile; endif;
	  ?>
  </div>

  <div class="load-more-button"></div>
</div>

<?php get_footer(); ?>
