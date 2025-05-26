<?php

require_once("functions.php");
require_once('smtp.php');

$time_start = microtime(true);

$query = mysqli_query($conn, "SELECT c.id, c.user_id, c.account_id, c.contact_list_id, c.subject, c.content FROM campaigns AS c LEFT JOIN campaign_meta AS cm ON c.id=cm.campaign_id WHERE c.is_completed='0' && c.active_status='2' && c.delete_status='0' && (cm.meta_key='schedule_time' && cm.meta_value<='$time_created') ORDER BY c.time_created ASC LIMIT 1");

// echo 'Check campaign query ends Total execution time in seconds: ' . (microtime(true) - $time_start)."<br>";

if(mysqli_num_rows($query) > 0) {

	while($result = mysqli_fetch_assoc($query)) {
		// echo 'start campaign query Total execution time in seconds: ' . (microtime(true) - $time_start)."<br>";
		$campaign_id = $result['id'];

		$contact_list = json_decode(campaign_meta($campaign_id, 'campaign_contact_details'), true);

		$campaign_emails = '"'.implode('","', $contact_list['contact_email_accounts']).'"';

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

		$href_links = campaign_meta($campaign_id, 'campaign_links_redirects');

		$content = html_entity_decode(htmlspecialchars_decode($result['content']));

		foreach($contact_list['contact_email_accounts'] as $email_address) {
			// echo "ok<br>";
			if(mysqli_num_rows(mysqli_query($conn, "SELECT * FROM campaign_emails WHERE campaign_id='$campaign_id' && email_address='$email_address' && active_status='1' && delete_status='0'")) == 0) {
				// echo 'check mail already send or not ends Total execution time in seconds: ' . (microtime(true) - $time_start)."<br>";

				$SMTP->clearAllRecipients();

				$SMTP->addToAddress(trim($email_address));

				$query = mysqli_query($conn, "INSERT INTO inbox(account_id, user_id, mailbox, from_address, to_address, is_important, subject, content, udate, time_created) VALUES('$account_id', '$user_id', 'sent', '$from_email', '$email_address', '', '$subject', '$content', '$time_created', '$time_created')");
				// echo 'New inbox ends Total execution time in seconds: ' . (microtime(true) - $time_start)."<br>";
				$inbox_id = mysqli_insert_id($conn);
				$inbox_meta_query = mysqli_query($conn, "INSERT INTO inbox_meta(inbox_id, meta_key, meta_value) VALUES('$inbox_id', 'message_id', ''), ('$inbox_id', 'from_address', '$from_title'), ('$inbox_id', 'cc_address', ''), ('$inbox_id', 'date', '$date'), ('$inbox_id', 'size', '')");
				// echo 'Inbox meta ends Total execution time in seconds: ' . (microtime(true) - $time_start)."<br>";

				if($href_links != '') {

					$href_links_array = explode(',', $href_links);
					foreach($href_links_array as $href_link) {

						$redirect_token = md5(rand(0, 100000).time().microtime(true));

						$content = str_replace($href_link, $dashboard_url.'redirect.php?token='.$redirect_token, $content);
						
						$link_redirect_query = mysqli_query($conn, "INSERT INTO tracking_links(user_id, inbox_id, href_links, redirect_token, type, time_created) VALUES('$user_id', '$inbox_id', '$href_link', '$redirect_token', '2', '$time_created')");

					}

				}

				$SMTP->addMessage($content);

				$campaign_emails_query = mysqli_query($conn, "INSERT INTO campaign_emails(campaign_id, email_address, inbox_id, time_created) VALUES('$campaign_id', '$email_address', '$inbox_id', '$time_created')");
				// echo 'New campaign emails add ends Total execution time in seconds: ' . (microtime(true) - $time_start)."<br>";

				if(campaign_meta($campaign_id, 'campaign_attachment_details') != '') {
					$attachments = explode(',', campaign_meta($campaign_id, 'campaign_attachment_details'));
					foreach($attachments as $attachment) {
						$attachment = trim($attachment);
						$SMTP->addAttachments($attachment);
						$attachment_info = pathinfo($dashboard_url.$attachment);
						$attachment_name = $attachment_info['basename'];
						$attachment_type = $attachment_info['extension'];
						$attachment_query = mysqli_query($conn, "INSERT INTO inbox_attachments(inbox_id, attachment_name, attachment_url, attachment_type, time_created) VALUES('$inbox_id', '$attachment_name', '$attachment', '$attachment_type', '$time_created')");
						// echo 'New Attachment ends Total execution time in seconds: ' . (microtime(true) - $time_start)."<br>";
					}
				}

				if(mysqli_num_rows(mysqli_query($conn, "SELECT * FROM campaign_emails WHERE campaign_id='$campaign_id' && email_address IN ($campaign_emails)")) == sizeof($contact_list['contact_email_accounts'])) {
					// echo 'check campaign complete ends Total execution time in seconds: ' . (microtime(true) - $time_start)."<br>";
					// echo "Completed<br>";
					$is_completed = mysqli_query($conn, "UPDATE campaigns SET is_completed='1', active_status='1' WHERE id='$campaign_id'");
					// echo 'mark campaign complete ends Total execution time in seconds: ' . (microtime(true) - $time_start)."<br>";
				}

				if($query && $inbox_meta_query && $campaign_emails_query) {
					if($SMTP->sendNormalEmail()) {
						// echo 'email sends ends Total execution time in seconds: ' . (microtime(true) - $time_start)."<br>";
						echo json_encode(array('status' => 'success', 'status_code' => '001'));
					} else {
						// echo 'email not send ends Total execution time in seconds: ' . (microtime(true) - $time_start)."<br>";
						$delete_inbox_query = mysqli_query($conn, "DELETE FROM inbox WHERE id='$inbox_id'");
						$delete_inbox_meta_query = mysqli_query($conn, "DELETE FROM inbox_meta WHERE inbox_id='$inbox_id'");
						$delete_attachment_query = mysqli_query($conn, "DELETE FROM inbox_attachments WHERE inbox_id='$inbox_id'");
						$delete_campaign_emails_query = mysqli_query($conn, "DELETE FROM campaign_emails WHERE campaign_id='$campaign_id' && inbox_id='$inbox_id' && email_address='$email_address'");
						echo json_encode(array('status' => 'error', 'status' => '002'));
					}
				} else {
					echo json_encode(array('status' => 'error', 'status' => '002'));
				}

			}
		}
	}

}



?>