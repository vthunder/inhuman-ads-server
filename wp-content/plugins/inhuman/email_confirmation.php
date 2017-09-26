<?php

  function inhuman_send_email_confirmation($current_user_id, $email) {
    $token = sha1(uniqid());
    update_user_meta($current_user_id, "inhuman_email_confirmation_token", $token);
    update_user_meta($current_user_id, "inhuman_email_confirmation_timestamp", time());

    $headers[] = 'From: Inhuman Ads <noreply@mg.sandmill.org>';
    $headers[] = 'Content-Type: text/html';
    $headers[] = 'charset=UTF-8';
    $subject = 'Activate your Inhuman Ads account';
    $message = "<p>Hi,</p>
<p>Thanks for using Inhuman Ads! Please activate your account by confirming this email address.</p>
<p><a href=\"https://.../token=$token\">Activate Now</a></p>
<p>This is an automated email; if you received it in error, no action is required.</p>
<p>Mozilla. 331 E Evelyn Ave, Mountain View, CA 94041<br>
<a href=\"https://www.mozilla.org/en-US/privacy/?utm_campaign=inhuman-ads-welcome&utm_content=fx-privacy&utm_medium=email&utm_source=email\">Mozilla Privacy Policy</a></p>";

    return wp_mail($to, $subject, $message, $headers);
  }

  function inhuman_check_email_confirmation($current_user_id, $token) {
    $stored_token = get_user_meta($current_user_id, "inhuman_email_confirmation_token", true);
    $ts = get_user_meta($current_user_id, "inhuman_email_confirmation_timestamp", true);
    $expiry = $ts + (1 * 24 * 60 * 60);

    if ($token == $stored_token) {
      delete_user_meta($current_user_id, "inhuman_email_confirmation_token");
      delete_user_meta($current_user_id, "inhuman_email_confirmation_timestamp");
      return (time() <= $expiry);
    }
    return false;
  }

?>
