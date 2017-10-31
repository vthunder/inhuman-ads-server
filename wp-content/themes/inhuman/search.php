<?php wp_enqueue_style('post', get_template_directory_uri() . "/styles/page/search.css"); ?>
<?php get_header('page'); ?>

<section class="title small">
  <div class="title-inner">
  </div>
</section>

<div class="content-wrapper">

  <div class="sidebars">
    <?php include('components/sidebar-submit-an-ad.php'); ?>
    <?php include('components/sidebar-latest-blog-posts.php'); ?>
  </div>

  <section class="main-wrapper main-content">
    <div class="main">
      <div class="page-body">
        <h1>Search: <?php echo get_query_var('s'); ?></h1>
        <?php $loop = new WP_Query(array('s' => get_search_query())); ?>
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
    </div>
  </section>
</div>

<?php get_footer(); ?>
