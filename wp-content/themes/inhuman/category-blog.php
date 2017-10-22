<?php get_header(); ?>
<?php wp_enqueue_style('post', get_template_directory_uri() . "/styles/post.css"); ?>
<?php get_sidebar(); ?>

<?php
  $loop = new WP_Query(inhuman_query("blog"));
	if ($loop->have_posts() ) : while ($loop->have_posts()) : $loop->the_post();
	get_template_part('blog-post', get_post_format());
	endwhile; endif;
?>

<?php get_footer('post'); ?>
