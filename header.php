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
    <script src="https://use.typekit.net/dkl0mwz.js" type="text/javascript"></script>
    <script type="text/javascript">
      try{Typekit.load();}catch(e){}
    </script>
    <?php wp_head();?>
  </head>

  <body>
    <header>
      <div class="header-container">
        <div class="nav-left">
          <a id="sidebar-toggle" href="#sidebar"><i class="fa fa-bars fa-lg fa-inverse"></i></a>
          <span class="nav-subscribe">Subscribe</span>
        </div>

        <div class="nav-center">
          <a class="sitelink" href="<?php echo site_url(); ?>">
            <h1>Inhuman Ads</h1>
            <h2>powered by <span class="firefox">Firefox Screenshots</span></h2>
          </a>
        </div>

        <div class="nav-right">
          <a class="nav-search" href="#sidebar-menu"><i class="fa fa-search fa-lg fa-inverse"></i></a>
        </div>
      </div>

      <div class="header-bottom">
        <div class="hero">
          <p class="hero-text-heading">Lorem ipsum dolor</p>
          <p class="hero-text-body">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
        </div>
      </div>
    </header>
