<?php

require_once("functions.php");

// // At start of script
// $time_start = microtime(true); 
// sleep(1);
// // Anywhere else in the script
// echo 'Total execution time in seconds: ' . (microtime(true) - $time_start)."<br>";

// die();

// $query = mysqli_query($conn, "DELETE FROM campaign_emails WHERE inbox_id IN (88, 89, 91)");
// $inbox = mysqli_query($conn, "DELETE FROM inbox WHERE id IN (88, 89, 91)");
// $inbox_meta = mysqli_query($conn, "DELETE FROM inbox_meta WHERE inbox_id IN (88, 89, 91)");
// $inbox_attachment = mysqli_query($conn, "DELETE FROM inbox_attachments WHERE inbox_id IN (88, 89, 91)");

// echo php_uname();
// echo PHP_OS;

// echo "<script>window.open('','_self').close();</script>";

// die();

echo '<pre>';
print_r($_SERVER);

$user_info = $_SERVER['HTTP_USER_AGENT'];

if(strpos($user_info, 'Chrome') == true) {
    echo 'This is Google Chrome Browser';
} else if(strpos($user_info, 'Firefox') == true) {
    echo 'This is Mozilla Firefox Browser';
} else if(strpos($user_info, 'IE') == true) {
    echo 'This is Internet Explorer Browser';
} else if(strpos($user_info, 'Safari') == true) {
    echo 'This is Safari Browser';
} else {
    echo 'Any other browser';
}

echo '</pre>';

?>