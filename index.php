<?php get_header(); ?>

<div class="grid">
	<div class="grid-sizer"></div>
	<div class="gutter-sizer"></div>
	<?php 
    $args = array(
      'post_type' => 'post',
      'meta_query'  => array(
        array(
          'key' => '_inhuman_meta_key_featured',
          'value' => 'on'
        )
      ),
      'meta_key' => '_inhuman_meta_key_sort',
      'orderby' => 'meta_value_num',
      'order' => 'ASC',
    );
    $loop = new WP_Query( $args );
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
<div class="grid">
	<div class="grid-sizer"></div>
	<div class="gutter-sizer"></div>
	<?php 
    $args = array(
      'post_type' => 'post',
      'meta_query'  => array(
        'relation' => 'OR',
        array(
          'key' => '_inhuman_meta_key_featured',
          'compare' => 'NOT EXISTS',
          'value' => ''
        ),
        array(
          'key' => '_inhuman_meta_key_featured',
          'value' => ''
        )
      )

    );
    $loop = new WP_Query( $args );
		if ( $loop->have_posts() ) : while ( $loop->have_posts() ) : $loop->the_post();
		get_template_part( 'card', get_post_format() );
		endwhile; endif;
	?>
</div>

<?php get_footer(); ?>
