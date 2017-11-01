<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title><?php echo get_bloginfo('name'); ?></title>
    <meta name="description" content="<?php echo get_bloginfo('description'); ?>" />
    <meta name="HandheldFriendly" content="True" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="shortcut icon" href="<?php echo get_bloginfo( 'template_directory' );?>/favicon.png">
    <meta property="og:url" content="<?php the_permalink(); ?>" />
    <meta property="og:type" content="website" />
    <meta property="og:title" content="<?php echo get_bloginfo('name'); ?>" />
    <meta property="og:description" content="<?php echo get_bloginfo('description'); ?>" />
    <meta property="og:image" content="https://inhumanads.com/wp-content/themes/inhuman/assets/inhuman-logo-2.png" />
    <?php wp_head();?>
  </head>

  <body>
    <header>
      <div class="header-top">
        <div class="powered-by">
          <span>Powered by</span>
          <a target="_blank" href="https://screenshots.firefox.com/">
            <img class="firefox-screenshots-logo" src="<?php tpldir(); ?>/assets/firefox-screenshots.png"></a>
        </div>
        <div class="header-top-right">
          <ul class="header-links">
            <li><a href="/about">What are inhuman ads?</a></li>
            <li><a href="/blog">Blog</a></li>
          </ul>
          <div class="header-search">
            <?php get_search_form(); ?>
          </div>
        </div>
      </div>
    </header>

    <?php inhuman_setup_js_vars(); ?>
