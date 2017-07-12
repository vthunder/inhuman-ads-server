<?php get_header(); ?>

<h2 class="blog-post-title"><?php the_title(); ?></h2>
<p class="blog-post-meta"><?php the_date(); ?> by <a href="#"><?php the_author(); ?></a></p>
<?php the_content(); ?>

<?php comments_template(); ?>

<?php get_footer(); ?>
