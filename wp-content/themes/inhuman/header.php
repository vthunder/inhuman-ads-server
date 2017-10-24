<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title><?php echo get_bloginfo( 'name' ); ?></title>
    <meta name="description" content="<?php echo get_bloginfo( 'description' ); ?>" />
    <meta name="HandheldFriendly" content="True" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="shortcut icon" href="<?php echo get_bloginfo( 'template_directory' );?>/favicon.png">
    <?php wp_head();?>
  </head>

  <body>
    <header class="<?php echo (is_single() || is_page() || $paged > 1)? "smaller" : ""; ?>">
      <div class="header-top">
        <div class="powered-by">
          <span>Powered by</span>
          <a href="https://screenshots.firefox.com/"><img class="firefox-screenshots-logo" src="<?php tpldir(); ?>/assets/firefox-screenshots.png"></a>
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
      <div class="header-bottom<?php echo (is_single() || is_page() || $paged > 1)? " small" : ""; ?>">
        <img class="clouds cloud-left" src="<?php tpldir(); ?>/assets/clouds-left.png">
        <a href="<?php echo site_url(); ?>"><img src="<?php tpldir(); ?>/assets/inhuman-logo-2.png"></a>
        <img class="clouds cloud-right" src="<?php tpldir(); ?>/assets/clouds-right.png">
        <h2>Money and robots made a mess of the web.<br>Humans will clean it up.</h2>
      </div>
    </header>
