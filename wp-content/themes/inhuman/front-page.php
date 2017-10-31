<?php
  $paged = (get_query_var('paged'))? get_query_var('paged') : 1;

  $query_type = get_query_var('e', 'latest');
  function s($emoji) {
    global $query_type;
    if ($emoji == $query_type)
      echo "selected";
  }
?>

<?php wp_enqueue_style('home', get_template_directory_uri() . "/styles/page/front-page.css"); ?>
<?php get_header(); ?>
<?php include('title.php'); ?>

<div class="content-wrapper">

  <div class="sidebars-top">
    <?php include('components/sidebar-submit-an-ad.php'); ?>
  </div>
  <div class="sidebars-bottom">
    <?php include('components/sidebar-latest-blog-posts.php'); ?>
    <?php include('components/sidebar-share-buttons.php'); ?>
  </div>
  
  <section class="main-wrapper main-content">
    <div class="main">
      <?php if ($paged == 1): ?>
        <h3>Top 10 Most Inhuman Ads</h3>
        <div class="top-posts">
          <button class="arrow arrow-left">&laquo;</button>
          <div class="top-posts-viewport">
            <div class="top-posts-list">
	            <?php
                $card_num = 1;
                $loop = new WP_Query(inhuman_query('popular', null, 10));
		            if ($loop->have_posts()): while ($loop->have_posts()): $loop->the_post();
                include(locate_template('card.php', false, false));
                $card_num++;
		            endwhile; endif;
                wp_reset_postdata();
	            ?>
            </div>
          </div>
          <button class="arrow arrow-right">&raquo;</button>
        </div>

        <h3>More Bad Ads</h3>
      <?php endif; ?>
      
      <div class="grid">
	      <?php
          $query_args = [
            'post_type' => ['inhuman_screenshot'],
            'meta_query'  => [
              ['key' => 'inhuman_meta_status', 'value' => 'publish']
            ],
            'posts_per_page' => 9,
            'paged' => $paged,
            'page' => $paged
          ];

          $loop = new WP_Query($query_args);
		      if ($loop->have_posts()): while ($loop->have_posts()): $loop->the_post();
		      get_template_part('card', get_post_format());
		      endwhile;
          wp_reset_postdata();

          $pagination_args = array(
            'base' => get_pagenum_link(1) . '%_%',
            'format' => 'page/%#%',
            'total' => $loop->max_num_pages,
            'current' => $paged,
            'show_all' => False,
            'end_size' => 1,
            'mid_size' => 2,
            'prev_next' => True,
            'prev_text' => __('&laquo;prev'),
            'next_text' => __('next&raquo;'),
            'type' => 'plain',
            'add_args' => false,
            'add_fragment' => ''
          );
          $paginate_links = paginate_links($pagination_args);
	      ?>
          <nav class="pagination">
            <?php echo $paginate_links; ?>
          </nav>
        <?php endif; ?>
      </div>
    </div>
  </section>
</div>

<?php get_footer(); ?>
