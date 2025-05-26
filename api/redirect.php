<?php

require_once("functions.php");


if(isset($_GET['token']) && $_GET['token'] != '') {

    $token = trim(strip_tags(mysqli_real_escape_string($conn, $_GET['token'])));

    $ip_address = $_SERVER['REMOTE_ADDR'];
    $browser = $_SERVER['HTTP_USER_AGENT'];
    $os = php_uname();

    if($token != '') {

        $token_query = mysqli_query($conn, "SELECT * FROM tracking_links WHERE token='$token' && active_status='1' && delete_status='0'");

        if(mysqli_num_rows($token_query) > 0) {
            $token_result = mysqli_fetch_assoc($token_query);

            $query = mysqli_query($conn, "INSERT INTO mail_tracking(tracking_link_id, ip_address, time_created) VALUES('$tracking_link_id', '$ip_address', '$time_created')");
            $mail_tracking_id = mysqli_insert_id($conn);
            $mail_tracking_query = mysqli_query($conn, "INSERT INTO mail_tracking_meta(mail_tracking_id, meta_key, meta_value) VALUES('$mail_tracking_id', 'browser_detail', '$browser'), ('$mail_tracking_id', 'os_detail', '$os')");

            if($query && $mail_tracking_query) {
                header('location:'.$token_result['href_links']);
            } else {
                header('location:'.$token_result['href_links']);
            }

        }

    }

}

?>