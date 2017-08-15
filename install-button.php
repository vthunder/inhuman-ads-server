<?php 
  function inhuman_install_button($atts) {
    $fx = "https://www.mozilla.org/firefox/new/?scene=2&utm_source=inhumanads.com&utm_medium=referral&utm_campaign=non-fx-button#download-fx";
    $xpi = "https://addons.mozilla.org/firefox/downloads/file/699286/inhuman_ads-0.2-an+fx.xpi?src=dp-btn-primary";

    if (strlen(strstr($_SERVER['HTTP_USER_AGENT'], "Firefox")) <= 0) {
      return '<a id="install_button" href="' . $fx . '" class="button button-primary">Only with Firefox — Get Firefox Now!</a>';
    } else {
      return '<a id="install_button" href="' . $xpi . '" class="button button-primary">Activate Inhuman Ads</a>';
    }
  }
  add_shortcode('inhuman_install_button', 'inhuman_install_button');
?>
