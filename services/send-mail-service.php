<?php


function wp_SendMail_MDF($recipient_email,$subject, $email_message)
{
  
    $headers = array(
        'Content-Type: text/plain; charset=UTF-8',
    );
    // Send the email
    $wp_mail_result = wp_mail($recipient_email,$subject, $email_message, $headers);

    if ($wp_mail_result) {
       return true;
    } else {
       return false;
    }
}
