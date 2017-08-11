<div id="sidebar">
  <div class="sidebar-content">

    <div class="sidebar-top">
      <a class="close-button" href="#sidebar">╳</a>

      <ul class="sidebar-menu">
        <?php wp_list_pages( '&title_li=' ); ?>
        <li><a href="">Subscribe <i class="fa fa-envelope-o"></i></a></li>
      </ul>

      <hr class="title-divider" />

      <a href="/" class="sitelink"><img class="sidebar-logo" src="<?php tpldir(); ?>/assets/inhuman-logo.svg"></a>
      <div class="poweredby">Powered by<br>Firefox Screenshots</div>

      <div class="icons">
        <a href="#"><img class="icon" src="<?php echo get_bloginfo('template_directory'); ?>/assets/icon-Twitter.svg"></a>
        <a href="#"><img class="icon" src="<?php echo get_bloginfo('template_directory'); ?>/assets/icon-github.svg"></a>
      </div>
    </div>

    <div class="sidebar-bottom">
      <a class="mozlogo" href="https://mozilla.org">
        <img src="<?php echo get_bloginfo('template_directory'); ?>/assets/mozilla-logo/moz-logo-bw-rgb.svg">
      </a>
      <ul>
        <li><a href="https://www.mozilla.org/en-US/about/legal/terms/mozilla/">Terms</a></li>
        <li><a href="https://www.mozilla.org/en-US/privacy/websites/">Privacy Notice</a></li>
        <li><a href="https://www.mozilla.org/en-US/about/legal/report-infringement/">Report IP Infringement</a></li>
        <li><a href="">Give Feedback</a></li>
      </ul>
    </div>
  </div>
</div>
