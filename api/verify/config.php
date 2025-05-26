<?php

$conn = mysqli_connect('localhost', 'root', '', 'email_marketing');

session_start();

$time_created = time();

$main_url = 'http://localhost/email-marketing/';
$dashboard_url = 'http://localhost/email-marketing/dashboard/';
$api_url = 'http://localhost/email-marketing/api/';

?>