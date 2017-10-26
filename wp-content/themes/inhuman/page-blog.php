<?php get_header(); ?>
<?php wp_enqueue_style('blog', get_template_directory_uri() . "/styles/blog.css"); ?>

<div class="submit-an-ad-sidebar">
  <a class="submit-an-ad" href="/contribute">
    <img src="<?php tpldir(); ?>/assets/submit-an-ad.png">
  </a>
  <h3>What is Inhuman Ads?</h3>
  <p>Inhuman Ads is a space for humans to point out the mess made by automated platforms and advertiser dollars, so we can one day clean it up. It's a forum for you to share the funny, frustrating, and infuriating ad placements you find in your online adventures. <a href="/about">Learn More&raquo;</a></p>
</div>

<div class="share-buttons-sidebar">
  <h3>Share Bad Ads</h3>
  <div class="share-buttons">
    <button class="share-facebook"><i class="fa fa-facebook"></i> Share us on Facebook</button>
    <button class="share-twitter"><i class="fa fa-twitter"></i> Tweet about us</button>
  </div>
</div>

<div class="main">
  <?php
    $loop = new WP_Query(inhuman_query("blog"));
	  if ($loop->have_posts() ) : while ($loop->have_posts()) : $loop->the_post();
	  get_template_part('blog-post', get_post_format());
	  endwhile; endif;
    wp_reset_postdata();
  ?>
</div>
  
<?php get_footer(); ?>
