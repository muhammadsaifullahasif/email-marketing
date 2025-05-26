<?php

$conn = mysqli_connect('localhost', 'root', '', 'email_marketing');

$time_created = time();

$content = htmlentities(htmlspecialchars(file_get_contents('../template/Anabel-MailChimp.html')));

// $content = htmlspecialchars(file_get_contents('../template/Anabel-MailChimp.html'));

// $content = file_get_contents('../template/Anabel-MailChimp.html');

echo $content;
die();

// $query = mysqli_query($conn, "INSERT INTO templates(template_name, template_content, content_type, time_created) VALUES('Anabel-MP', '$content', 'html', '$time_created')");

// if($query) {
//    echo "Success";
// } else {
//    echo "Error";
// }

/*
$server = '{wirecoder.com:993/ssl}';
$connection = imap_open($server, 'info@wirecoder.com', '7S@!fullah');
$mailboxes = imap_list($connection, $server, '*');
echo '<pre>';
print_r($mailboxes);
*/

// echo round(filesize('../attachments/info@wirecoder.com/namecheap-order-104582746.pdf') / 1024);

// $pathinfo = pathinfo('http://localhost/email-marketing/dashboard/attachments/info@wirecoder.com/cpanel-logo-tiny.png');
// echo $pathinfo['basename'];
// echo '<pre>';
// print_r($pathinfo);

// $final = '<li>Jain R.K. and Iyengar S.R.K., â€œAdvanced Engineering Mathematicsâ€, Narosa Publications,</li>';

// $final = str_replace("Â", "", $final);
// $final = str_replace("â€™", "'", $final);
// $final = str_replace("â€œ", '"', $final);
// $final = str_replace('â€“', '-', $final);
// $final = str_replace('â€', '"', $final);

// $final = str_replace('â€', '"', str_replace('â€“', '-', str_replace('â€œ', '"', str_replace('â€™', "'", str_replace('Â', "", $final)))));

// echo $final;

// $date = 'Tue, 8 Nov 2022 19:29:19 +0100';
// echo date('r');
