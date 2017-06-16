<div class="footer">
  <div class="footer-right">
    <div class="footer-menu">
      <i class="footer-menu-chevron fa fa-caret-right fa-2x"></i>
      <span class="brought-by"><a href="https://mozilla.org"><span class="mozlogo">moz://a</span></a></span>
      <ul class="terms">
        <li><a href="">Terms</a></li>
        <li>&bull; <a href="">Privacy Notice</a></li>
        <li>&bull; <a href="">Report IP Infringement</a></li>
        <li>&bull; <a href="">Give Feedback</a></li>
        <li>&bull; <a href="">GitHub</a></li>
      </ul>
    </div>
    <a class="footer-button fa-stack fa-2x" href="#">
      <i class="fa fa-circle fa-stack-2x fa-inverse"></i>
      <i class="fa fa-question-circle fa-stack-2x"></i>
    </a>
  </div>
</div>
<?php
  wp_enqueue_script('require', get_bloginfo('template_directory') . '/vendor/requirejs/require.js');
  wp_enqueue_script('main', get_bloginfo('template_directory') . '/js/main.js');
  wp_localize_script('main', 'php_data', array(
    'base_uri' => get_template_directory_uri(),
    'ajax_url' => admin_url('admin-ajax.php'),
    'nonce' => wp_create_nonce('inhuman-ads-nonce')
  ));
  wp_footer();
?>
</body>
</html>
