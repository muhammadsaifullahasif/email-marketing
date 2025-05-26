<?php

require_once('smtp.php');

$emails = array('muhammadsaifullahasif@hotmail.com', 'muhammadsaifullahasif@gmail.com', 'muhammadsaifullahasif@outlook.com');

$SMTP = new SMTP('wirecoder.com', 'info@wirecoder.com', '7S@!fullah');

foreach($emails as $to) {

    $SMTP->clearAllRecipients();

    $SMTP->addSubject('Lead Generation');

    $SMTP->addMessage('Hello Clients');

    $SMTP->addToAddress($to);

    if($SMTP->sendNormalEmail()) {
        echo 'Send Successfully';
    } else {
        echo "Email Not Sent";
    }
    echo "<br>";

}


die();



$headers = 'From: info@wirecoder.com' . "\r\n" .
    'Reply-To: no-reply@wirecoder.com' . "\r\n" .
    'X-Mailer: PHP/' . phpversion();

$emails = array('muhammadsaifullahasif@hotmail.com', 'muhammadsaifullahasif@gmail.com', 'muhammadsaifullahasif@outlook.com');

foreach($emails as $to) {
    if(mail($to, 'Lead Generation', 'Hello Clients', $headers)) {
        echo "Send Successfully";
    } else {
        echo "<pre>".print_r(error_get_last())."</pre>";
    }
    echo "<br>";
}




?>