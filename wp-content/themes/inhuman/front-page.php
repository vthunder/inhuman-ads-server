<?php
  $paged = (get_query_var('paged'))? get_query_var('paged') : 1;

  $query_type = get_query_var('e', 'latest');
  function s($emoji) {
    global $query_type;
    if ($emoji == $query_type)
      echo "selected";
  }
?>
<?php include('header.php'); ?>
<?php inhuman_setup_js_vars(); ?>

<div class="submit-an-ad-sidebar">
  <a class="submit-an-ad" href="/contribute">
    <img src="<?php tpldir(); ?>/assets/submit-an-ad.png">
  </a>
  <h3>What is Inhuman Ads?</h3>
  <p>Inhuman Ads is a space for humans to point out the mess made by automated platforms and advertiser dollars, so we can one day clean it up. It's a forum for you to share the funny, frustrating, and infuriating ad placements you find in your online adventures. <a href="/about">Learn More&raquo;</a></p>
</div>

<div class="blog-posts-sidebar">
  <div class="latest-blog-posts-header">
    <h3>Latest Blog Posts</h3>
    <span><a href="/blog">See all&raquo;</a></span>
  </div>
  <div class="latest-blog-posts">
	  <?php
      $loop = new WP_Query(inhuman_query('blog', null, 10));
		  if ($loop->have_posts()): while ($loop->have_posts()): $loop->the_post();
		  get_template_part('blog-post-summary', get_post_format());
		  endwhile; endif;
	  ?>
  </div>
</div>

<div class="share-buttons-sidebar">
  <h3>Share Bad Ads</h3>
  <div class="share-buttons">
    <button class="share-facebook"><i class="fa fa-facebook"></i> Share us on Facebook</button>
    <button class="share-twitter"><i class="fa fa-twitter"></i> Tweet about us</button>
  </div>
</div>

<div class="main">
  <?php if ($paged == 1): ?>
    <h3>Top 10 Most Inhuman Ads</h3>
    <div class="top-posts">
      <button class="arrow arrow-left"><i class="fa fa-caret-left"></i></button>
      <div class="top-posts-viewport">
        <div class="top-posts-list">
	        <?php
            $card_num = 1;
            $loop = new WP_Query(inhuman_query('popular', null, 10));
		        if ($loop->have_posts()): while ($loop->have_posts()): $loop->the_post();
            include(locate_template('card.php', false, false));
            $card_num++;
		        endwhile; endif;
	        ?>
        </div>
      </div>
      <button class="arrow arrow-right"><i class="fa fa-caret-right"></i></button>
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

<?php get_footer(); ?>
