<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title><?php echo get_bloginfo( 'name' ); ?></title>
    <meta name="description" content="<?php echo get_bloginfo( 'description' ); ?>" />
    <meta name="HandheldFriendly" content="True" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="shortcut icon" href="<?php echo get_bloginfo( 'template_directory' );?>/favicon.ico">
    <noscript>
      <link rel="stylesheet" type="text/css" href="<?php echo get_bloginfo('template_directory') . '/styles/noscript.css' ?>">
    </noscript>
    <?php wp_head();?>
  </head>

  <body>
    <header class="<?php echo (is_single() || is_page())? "smaller" : ""; ?>">
      <div class="header-container">
        <div class="nav-left">
          <a id="sidebar-toggle" href="#sidebar"><i class="fa fa-bars fa-lg fa-inverse"></i></a>
          <?php if (!is_user_logged_in()): ?>
            <span class="nav-contribute">
              <a class="contribute-link" href="/contribute">
                <i class="fa fa-plus-square fa-lg"></i> Contribute a screenshot
              </a>
            </span>
          <?php endif; ?>
        </div>

        <div class="nav-center">
          <a class="sitelink" href="<?php echo site_url(); ?>">
            <img class="title-logo" src="<?php tpldir(); ?>/assets/inhuman-logo.svg">
            <h2>powered by <span class="firefox">Firefox Screenshots</span></h2>
          </a>
        </div>

        <div class="nav-right">
          <a class="nav-search" href="#sidebar-menu"><i class="fa fa-search fa-lg fa-inverse"></i></a>
          <?php get_search_form(); ?>
        </div>
      </div>
    </header>

    <div class="header-bottom<?php echo (is_single() || is_page())? " hide" : ""; ?>">
      <div class="hero-left"></div>
      <div class="hero">
        <p class="hero-text-heading">Money and robots made a mess of the web.<br>Humans will clean it up.</p>
        <div class="hero-text-body">
          <ul>
            <li><a href="/about">About</a></li>
            <li><a href="/high-scores">High Scores</a></li>
            <li><a href="/blog">Blog</a></li>
          </ul>
        </div>
        
      </div>
      <div class="hero-right"></div>
    </div>

