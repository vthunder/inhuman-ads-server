<?php get_header(); ?>

<a id="sidebar-toggle" href="#sidebar-menu" class="fa-stack fa-lg">
  <i class="fa fa-square fa-stack-2x"></i>
  <i class="fa fa-bars fa-stack-1x fa-inverse"></i>
</a>

<div id="sidebar-menu">
  <div class="login">
    <ul>
      <?php if (is_user_logged_in()): ?>
        <li>Signed in as <?php echo wp_get_current_user()->display_name; ?>
          <a class="signout-button" href="<?php echo wp_logout_url(home_url()); ?>">Sign out</a>
        </li>
      <?php else: ?>
        <li><?php echo do_shortcode('[auth0 show_as_modal="true" modal_trigger_name="Sign in"]'); ?></li>
      <?php endif; ?>
    </ul>
  </div>
  <br/>
  <h3>Contribute a screenshot</h3>
  <form class="add-screenshot" method="POST" action="inhuman-add-screenshot">
    <input name="screenshot-url" type="text"
           placeholder="Firefox Screenshots link">
    <button href="#">Add</button>
  </form>
  <!-- Your content -->
  <ul class="sidebar-bottom">
    <li><a href="#">List 1</a></li>
    <li class="active"><a href="#">List 2</a></li>
    <li><a href="#">List 3</a></li>
  </ul>
</div>

<div class="featured-posts grid">
	<div class="grid-sizer"></div>
	<div class="gutter-sizer"></div>
	<?php 
    $loop = new WP_Query($inhuman_featured_query);
		if ( $loop->have_posts() ) : while ( $loop->have_posts() ) : $loop->the_post();
		get_template_part( 'card', get_post_format() );
		endwhile; endif;
    wp_reset_query();
	?>
</div>
<ul class="category-selector">
	<li><a href="" class="category-button">All</a></li>
	<li><a href="" class="category-button">Screenshots</a></li>
	<li><a href="" class="category-button">Articles</a></li>
</ul>
<div class="all-posts grid">
	<div class="grid-sizer"></div>
	<div class="gutter-sizer"></div>
	<?php 
    $loop = new WP_Query($inhuman_posts_query);
		if ( $loop->have_posts() ) : while ( $loop->have_posts() ) : $loop->the_post();
		get_template_part( 'card', get_post_format() );
		endwhile; endif;
	?>
</div>

<div class="load-more-button"></div>

<?php get_footer(); ?>
