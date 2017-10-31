<?php wp_enqueue_style('post', get_template_directory_uri() . "/styles/page/page-blog.css"); ?>
<?php get_header('page'); ?>

<section class="title small">
  <div class="title-inner">
    <img class="clouds cloud-left" src="<?php tpldir(); ?>/assets/clouds-left.png">
    <h1 class="logo">Inhuman Blog</h1>
    <img class="clouds cloud-right" src="<?php tpldir(); ?>/assets/clouds-right.png">
  </div>
</section>

<div class="content-wrapper">

  <div class="sidebars">
    <?php include('components/sidebar-submit-an-ad.php'); ?>
    <?php include('components/sidebar-share-buttons.php'); ?>
  </div>

  <section class="main-wrapper main-content">
    <div class="main">
      <?php
        $loop = new WP_Query(inhuman_query("blog"));
	      if ($loop->have_posts() ) : while ($loop->have_posts()) : $loop->the_post();
	      get_template_part('blog-post', get_post_format());
	      endwhile; endif;
        wp_reset_postdata();
      ?>
    </div>
  </section>
</div>  
<?php get_footer(); ?>
