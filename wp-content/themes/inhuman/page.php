<?php get_header(); ?>
<?php wp_enqueue_style('post', get_template_directory_uri() . "/styles/post.css"); ?>
<?php //wp_enqueue_style('page', get_template_directory_uri() . "/styles/page.css"); ?>
<?php get_sidebar(); ?>

<div class="page-body">
  <?php while ( have_posts() ) : the_post(); ?>
    <?php get_template_part( 'content', 'page' ); ?>
  <?php endwhile; ?>
  <?php the_content(); ?>
</div>

<?php get_footer('post'); ?>
