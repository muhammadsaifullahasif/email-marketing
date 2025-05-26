<?php

require_once("functions.php");
require_once('smtp.php');

$time_start = microtime(true);

$query = mysqli_query($conn, "SELECT i.id, i.user_id, i.admin_id, i.account_id, i.mailbox, i.from_address, i.to_address, i.is_starred, i.is_important, i.is_tracked, i.subject, i.content FROM inbox AS i LEFT JOIN inbox_meta AS im ON i.id=im.inbox_id WHERE i.is_completed='0' && i.active_status='2' && i.delete_status='0' && (im.meta_key='schedule_time' && im.meta_value<='$time_created') ORDER BY i.time_created");

// echo 'Check campaign query ends Total execution time in seconds: ' . (microtime(true) - $time_start)."<br>";

if(mysqli_num_rows($query) > 0) {

	while($result = mysqli_fetch_assoc($query)) {
		// echo 'start campaign query Total execution time in seconds: ' . (microtime(true) - $time_start)."<br>";
		$inbox_id = $result['id'];

		// $contact_list = json_decode(campaign_meta($campaign_id, 'campaign_contact_details'), true);

		// $campaign_emails = '"'.implode('","', $contact_list['contact_email_accounts']).'"';

		$account_id = $result['account_id'];
		$user_id = $result['user_id'];

		$date = date('r');

		$from_address_query = mysqli_query($conn, "SELECT * FROM accounts WHERE (user_id='$user_id' || admin_id='$user_id') && id='$account_id' && active_status='1' && delete_status='0' && verified_status='1'");
		// echo 'Select Account Data ends Total execution time in seconds: ' . (microtime(true) - $time_start)."<br>";

		if(mysqli_num_rows($from_address_query) > 0) {

			$from_address_result = mysqli_fetch_assoc($from_address_query);
			$from_email = $from_address_result['account_email'];
			$from_title = $from_address_result['account_title'];
			$account_host = explode('@', $from_address_result['account_email']);

		}

		// echo 'SMTP start Total execution time in seconds: ' . (microtime(true) - $time_start)."<br>";

		$SMTP = new SMTP($account_host[1], $from_address_result['account_email'], $from_address_result['account_password']);
		// echo 'SMTP end Total execution time in seconds: ' . (microtime(true) - $time_start)."<br>";
		$subject = $result['subject'];
		$SMTP->addSubject(trim($subject));

		// $href_links = inbox_meta($inbox_id, 'inbox_links_redirects');

		$content = html_entity_decode(htmlspecialchars_decode($result['content']));

		$SMTP->clearAllRecipients();
		$SMTP->addToAddress(trim($result['to_address']));

		if($result['is_tracked'] == 1) {
			$tracking_type = 0;
			$redirect_token = md5(rand(0, 100000).time().microtime(true));
			$track_link = $dashboard_url.'tracking.php?type='.$tracking_type.'&redirect_token='.$redirect_token;

			$content = "<img src='".$track_link."' width='1px' height='1px'>".$content;

			$SMTP->addMessage(trim($content));
		} else {
			$SMTP->addMessage(trim($content));
		}

		$attachment_query = mysqli_query($conn, "SELECT attachment_url FROM inbox_attachments WHERE inbox_id='$inbox_id' && active_status='1' && delete_status='0'");
		if(mysqli_num_rows($attachment_query) > 0) {
			while($attachment_result = mysqli_fetch_assoc($attachment_query)) {
				$SMTP->addAttachments(trim($attachment_result['attachment_url']));
			}
		}

		if($result['is_important'] == 1) {
			$is_important = 1;
		} else {
			$is_important = 2;
		}

		if($SMTP->sendNormalEmail($is_important)) {
			// echo 'email sends ends Total execution time in seconds: ' . (microtime(true) - $time_start)."<br>";
			$update_inbox = mysqli_query($conn, "UPDATE inbox SET is_completed='1' WHERE id='$inbox_id'");
			echo json_encode(array('status' => 'success', 'status_code' => '001'));
		} else {
			// echo 'email not send ends Total execution time in seconds: ' . (microtime(true) - $time_start)."<br>";
			echo json_encode(array('status' => 'error', 'status' => '002'));
		}

	}

}



?>