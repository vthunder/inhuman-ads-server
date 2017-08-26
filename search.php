<?php
  get_header();
  wp_enqueue_style('post', get_template_directory_uri() . "/styles/post.css");
  $loop = new WP_Query(array('s' => get_search_query()));
?>
<div class="page-body">
  <h1>Search: <?php echo get_query_var('s'); ?></h1>
  <?php if ($loop->have_posts()): ?>
    <ul class="search-results">
      <?php
	      while ($loop->have_posts()) : $loop->the_post();
	      get_template_part('search-result', get_post_format());
	      endwhile;
      ?>
    </ul>
  <?php else: ?>
    <h3>No results</h3>
    <p>Nothing matched your search. Try again with different keywords.</p>
  <?php endif; ?>
</div>
<?php get_sidebar(); ?>
<?php get_footer(); ?>
