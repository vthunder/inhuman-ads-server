<?php 
  function inhuman_ua_fx($atts, $content = null) {
    if (strlen(strstr($_SERVER['HTTP_USER_AGENT'], "Firefox")) > 0) {
      return $content;
    } else {
      return '';
    }
  }
  add_shortcode('ua_fx', 'inhuman_ua_fx');

  function inhuman_ua_other($atts, $content = null) {
    if (strlen(strstr($_SERVER['HTTP_USER_AGENT'], "Firefox")) <= 0) {
      return $content;
    } else {
      return '';
    }
  }
  add_shortcode('ua_other', 'inhuman_ua_other');
?>
