<?php get_header(); ?>

<?php get_sidebar(); ?>

<div class="featured-posts grid">
	<div class="grid-sizer"></div>
	<div class="gutter-sizer"></div>
	<?php 
    $loop = new WP_Query($inhuman_featured_query);
		if ( $loop->have_posts() ) : while ( $loop->have_posts() ) : $loop->the_post();
		get_template_part( 'card', get_post_format() );
		endwhile; endif;
    wp_reset_query();
	?>
</div>
<ul class="category-selector">
	<li><a href="" class="category-button">All</a></li>
	<li><a href="" class="category-button">Screenshots</a></li>
	<li><a href="" class="category-button">Articles</a></li>
</ul>
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

<?php get_footer(); ?>
