<div class="article-body">
	<h2 class="article-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
	<p class="article-meta"><?php the_date(); ?> by <a href="#"><?php the_author(); ?></a></p>
  <div class="article-content">
    <?php the_post_thumbnail();
      echo get_post(get_post_thumbnail_id())->post_excerpt; ?>
    <?php the_content(); ?>
  </div>
</div>
