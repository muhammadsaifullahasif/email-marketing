<?php

require_once('config.php');
require_once('classes/imap_original.php');
session_start();

$user_id = $_SESSION['email_marketing_user_id'];
$account_id = $_SESSION['email_marketing_account_id'];

if($user_id != '' && $user_id != 0 && $account_id != '' && $account_id != 0) {

    $query = mysqli_query($conn, "SELECT * FROM accounts WHERE id='$account_id' && (user_id='$user_id' || admin_id='$user_id')");

    if(mysqli_num_rows($query) > 0) {

        $result = mysqli_fetch_assoc($query);
        $user_id = $result['user_id'];
        $email = new Imap('../attachments/'.$result['account_email']);
        $account_host = explode('@', $result['account_email']);
        $server = '{'.$account_host[1].':993/ssl}';
        $connection = imap_open($server, $result['account_email'], $result['account_password']) or die('Cannot connect to mailbox: ' . imap_last_error());

        $mailbox = array(
            'inbox' => 'INBOX', 
            'archive' => 'INBOX.Archive', 
            'trash' => 'INBOX.Trash', 
            'sent' => 'INBOX.Sent', 
            'drafts' => 'INBOX.Drafts', 
            'spam' => 'INBOX.spam'
        );

        foreach($mailbox as $mailbox_key => $mailbox_value) {
            
            $new_mailbox = '{'.$account_host[1].':993/ssl}'.$mailbox_value;
            imap_reopen($connection, $new_mailbox);
            $count = imap_num_msg($connection);
            $mailbox_key = 'total_'.$mailbox_value.'_msg';
            $inbox = mysqli_query($conn, "INSERT INTO account_meta(account_id, meta_key, meta_value) VALUES('$account_id', '$mailbox_key', '$count')");

        }
        header('location: index.php');

    }
    
}

?>