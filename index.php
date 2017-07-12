<?php get_header(); ?>

<?php get_sidebar(); ?>

<div class="container">

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
