<?php get_header(); ?>
<?php wp_enqueue_style('post', get_template_directory_uri() . "/styles/post.css"); ?>
<?php get_sidebar(); ?>
<?php inhuman_setup_js_vars(); ?>

<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
  <div class="article-body">
	  <h2 class="article-title"><?php the_title(); ?></h2>
	  <p class="article-meta"><?php the_date(); ?> by <a href="#"><?php the_author(); ?></a></p>
    <div class="article-content">
      <?php the_content(); ?>
    </div>
  </div>
<?php endwhile; else : ?>
<p><?php esc_html_e( 'Sorry, no posts matched your criteria.' ); ?></p>
<?php endif; ?>

<?php get_footer(); ?>
