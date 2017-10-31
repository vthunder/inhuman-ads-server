<section class="sidebar-wrapper sidebar2 latest-blog-posts">
  <div class="sidebar">
    <div class="latest-blog-posts-header">
      <h3>Latest Blog Posts</h3>
      <a class="seeall" href="/blog">See all&raquo;</a>
    </div>
    <div class="latest-blog-posts">
	    <?php
        $loop = new WP_Query(inhuman_query('blog', null, 10));
		    if ($loop->have_posts()): while ($loop->have_posts()): $loop->the_post();
		    get_template_part('blog-post-summary', get_post_format());
		    endwhile; endif;
        wp_reset_postdata();
      ?>
    </div>
  </div>
</section>
