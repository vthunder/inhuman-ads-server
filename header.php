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
    <?php 
      wp_enqueue_style('main', get_bloginfo('template_directory') . '/style.css' );
      wp_enqueue_style('sidr', get_bloginfo('template_directory') . '/vendor/sidr/dist/stylesheets/jquery.sidr.dark.min.css' );
      wp_enqueue_style('buttons', get_bloginfo('template_directory') . '/vendor/buttons/css/buttons.css' );
      wp_enqueue_style('font-awesome', get_bloginfo('template_directory') . '/vendor/font-awesome/css/font-awesome.css' );
    ?>
    <?php wp_head();?>
  </head>

  <body>
    <div class="header">
    </div>
