<?php

session_start();
$conn = mysqli_connect('localhost', 'root', '', 'email_marketing');
$time_created = time();

if(isset($_GET['action']) && isset($_GET['verify_method']) && $_GET['action'] == 'verify' && $_GET['verify_method'] == 'email') {

	if(isset($_GET['type']) && strip_tags($_GET['type']) == 1) { // Verify
		if(isset($_GET['verify_type']) && strip_tags($_GET['verify_type']) == 1) { // Account Verify
			$associate_id = strip_tags($_GET['associate_id']);
			$verify_item = strip_tags($_GET['verify_item']);
			$verify_code = strip_tags($_GET['verify_code']);

			if($associate_id != '' && $verify_item != '' && $verify_code != '') {
				$query = mysqli_query($conn, "SELECT * FROM verification WHERE user_id='$associate_id' && verify_item_id='$verify_item' && active_status='1' ORDER BY time_created DESC LIMIT 1");
				if(mysqli_num_rows($query) > 0) {
					$result = mysqli_fetch_assoc($query);
					if($result['verify_code'] == $verify_code) {
						$update_verify = mysqli_query($conn, "UPDATE verification SET active_status='0' WHERE user_id='$associate_id' && verify_item_id='$verify_item' && verify_code='$verify_code'");
						$update_account = mysqli_query($conn, "UPDATE accounts SET verified_status='1' WHERE id='$verify_item'");
						$_SESSION['email_marketing_account_id'] = $verify_item;
						$_SESSION['email_marketing_user_id'] = $associate_id;
						header('location: fetch_emails.php');
					}
				}
			}
		}
	}

}

