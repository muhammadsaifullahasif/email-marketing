<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$data = json_decode(file_get_contents("php://input"), true);

// require_once("config.php");
require_once("functions.php");
require_once('smtp.php');

if(isset($data['action']) && $data['action'] == 'download_emails') {
	require_once('classes/imap_original.php');

	$account_id = trim(strip_tags(mysqli_real_escape_string($conn, $data['account_id'])));
	$user_id = trim(strip_tags(mysqli_real_escape_string($conn, $data['user_id'])));

	if($account_id != '' && $user_id != '') {
		$query = mysqli_query($conn, "SELECT * FROM accounts WHERE id='$account_id' && (user_id='$user_id' || admin_id='$user_id') && active_status='1' && delete_status='0'");

		if(mysqli_num_rows($query) > 0) {
			$result = mysqli_fetch_assoc($query);

			$email = new Imap('attachments/'.$result['account_email']);
			$account_host = explode('@', $result['account_email']);

			$mailbox = array(
				'inbox' => 'INBOX', 
				'archive' => 'INBOX.Archive', 
				'trash' => 'INBOX.Trash', 
				'sent' => 'INBOX.Sent', 
				'drafts' => 'INBOX.Drafts', 
				'spam' => 'INBOX.spam'
			);
			$i = 1;

			foreach($mailbox as $mailbox_key => $mailbox_value) {
				$total_downloaded_messages = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM inbox WHERE account_id='$account_id' && mailbox='$mailbox_key'"));
				$connection = $email->connect('{'.$account_host[1].':993/ssl}'.$mailbox_value, $result['account_email'], $result['account_password']);
				$total_downloaded_messages += account_meta($account_id, 'total_'.$mailbox_value.'_msg');
				if(isset($data['current_msg']) && isset($data['total_msg'])) {
					$email->setLimit(1);
					$inbox = $email->getMessages('html', 'desc', '$total_downloaded_messages', $data['total_msg']);
				} else {
					$inbox = $email->getMessages('html', 'desc', $total_downloaded_messages);
				}
				
				if($inbox) {
					
					
					foreach ($inbox as $v) {

						$subject = trim($v['subject']);
						$udate = trim($v['date']);
						$message_id = trim($v['message_id']);

						$to_address = '';
						$date = '';
						$size = '';
						$msg_no = '';
						$recent = '';
						$flagged = '';
						$answered = '';
						$deleted = '';
						$seen_status = '';
						$draft = '';

						foreach($v['message_number'] as $key => $value) {
							if($key == 'to') {
								$to_address = trim($value);
							} else if($key == 'date') {
								$date = trim($value);
							} else if($key == 'size') {
								$size = trim($value);
							} else if($key == 'msgno') {
								$msg_no = trim($value);
							} else if($key == 'recent') {
								$recent = trim($value);
							} else if($key == 'flagged') {
								$flagged = trim($value);
							} else if($key == 'answered') {
								$answered = trim($value);
							} else if($key == 'deleted') {
								$deleted = trim($value);
							} else if($key == 'seen') {
								$seen_status = trim($value);
							} else if($key == 'draft') {
								$draft = trim($value);
							}
						}

						$uid = trim($v['uid']);
						$references = trim($v['references']);
						if(isset($v['from'][0]['address'])) {
							$from_address = trim($v['from'][0]['address']);
						} else {
							$from_address = '';
						}
						if(isset($v['from'][0]['name'])) {
							$from_name = trim($v['from'][0]['name']);
						} else {
							$from_name = '';
						}
						if(isset($v['cc'][0]['address'])) {
							$cc_address = trim($v['cc'][0]['address']);
						} else {
							$cc_address = '';
						}
						$message = htmlentities(htmlspecialchars($v['message']));

						$inbox_query = mysqli_query($conn, "INSERT INTO inbox(account_id, user_id, uid, mailbox, from_address, to_address, is_starred, is_important, seen_status, subject, content, udate, time_created) VALUES('{$account_id}', '{$user_id}', '{$uid}', '{$mailbox_key}', '{$from_address}', '{$to_address}', '0', '{$flagged}', '{$seen_status}', '{$subject}', '{$message}', '{$udate}', '{$time_created}')");

						$inbox_id = mysqli_insert_id($conn);

						$inbox_meta_query = mysqli_query($conn, "INSERT INTO inbox_meta(inbox_id, meta_key, meta_value) VALUES
							('$inbox_id', 'message_id', '$message_id'), 
							('$inbox_id', 'from_name', '$from_name'), 
							('$inbox_id', 'cc_address', '$cc_address'), 
							('$inbox_id', 'date', '$date'), 
							('$inbox_id', 'size', '$size')
						");

						if(sizeof($v['attachments']) > 0) {
							foreach($v['attachments'] as $attachments) {
								$attachment_url = $home_url.'api/attachments/'.$result['account_email'].'/'.$attachments;
								$attachment_type = substr(strrchr($attachment_url, '.'), 1);
								$attachment_query = mysqli_query($conn, "INSERT INTO inbox_attachments(inbox_id, attachment_url, attachment_type, time_created) VALUE('$inbox_id', '$attachment_url', '$attachment_type', '$time_created')");
							}
						}
					}
					
					
				}
			}
			echo json_encode(array('status' => 'success', 'status_code' => '001'));
			
		}
	}

}



if(isset($data['action']) && $data['action'] == 'display_subscriptions') {

	$query = mysqli_query($conn, "SELECT * FROM subscriptions WHERE active_status='1' && delete_status='0'");

	if(mysqli_num_rows($query) > 0) {
		while($result = mysqli_fetch_assoc($query)) {
			$subscription_id = $result['id'];
			$meta_query = mysqli_query($conn, "SELECT * FROM subscription_meta WHERE subscription_id='$subscription_id'");
			if(mysqli_num_rows($meta_query) > 0) {
				while($meta_result = mysqli_fetch_assoc($meta_query)) {
					$result[$meta_result['meta_key']] = json_decode($meta_result['meta_value']);
				}
			}

			$output[] = $result;
		}

		echo json_encode($output, true);
	}
}

if(isset($data['action']) && $data['action'] == 'single_subscription') {

	$id = trim(strip_tags(mysqli_real_escape_string($conn, $data['subscription_id'])));
	$query = mysqli_query($conn, "SELECT * FROM subscriptions WHERE id='$id' && active_status='1' && delete_status='0'");

	if(mysqli_num_rows($query) > 0) {
		$result = mysqli_fetch_assoc($query);

		$meta_query = mysqli_query($conn, "SELECT * FROM subscription_meta WHERE subscription_id='$id'");
		if(mysqli_num_rows($meta_query) > 0) {
			while($meta_result = mysqli_fetch_assoc($meta_query)) {
				$meta[$meta_result['meta_key']] = json_decode($meta_result['meta_value']);
			}
		}

		echo json_encode(array_merge(array('status' => 'success', 'status_code' => '001') ,array_merge($result, $meta)));

	} else {
		echo json_encode(array('status' => 'error', 'status_code' => '004'));
	}
}

if(isset($data['action']) && $data['action'] == 'username_availability') {

	$user_login = trim(strip_tags(mysqli_real_escape_string($conn, $data['user_login'])));

	if($user_login != '') {
		if(mysqli_num_rows(mysqli_query($conn, "SELECT * FROM users WHERE user_login='$user_login' && active_status='1' && delete_status='0'")) == 0) {
			echo json_encode(array('status' => 'success', 'status_code' => '001', 'message' => 'Username available'));
		} else {
			echo json_encode(array('status' => 'error', 'status_code' => '004', 'message' => 'Username already exist'));
		}
	}

}

if(isset($data['action']) && $data['action'] == 'email_availability') {

	$user_email = trim(strip_tags(mysqli_real_escape_string($conn, $data['user_email'])));

	if($user_email != '') {
		if(mysqli_num_rows(mysqli_query($conn, "SELECT * FROM users WHERE user_email='$user_email' && active_status='1' && delete_status='0'")) == 0) {
			echo json_encode(array('status' => 'success', 'status_code' => '001', 'message' => 'Email available'));
		} else {
			echo json_encode(array('status' => 'error', 'status_code' => '004', 'message' => 'Email already exist'));
		}
	}

}

if(isset($data['action']) && $data['action'] == 'user_signup') {

	$first_name = trim(strip_tags(mysqli_real_escape_string($conn, $data['first_name'])));
	$last_name = trim(strip_tags(mysqli_real_escape_string($conn, $data['last_name'])));
	$display_name = $first_name." ".$last_name;
	$user_login = trim(strip_tags(mysqli_real_escape_string($conn, $data['user_login'])));
	$user_email = trim(strip_tags(mysqli_real_escape_string($conn, $data['user_email'])));
	$user_pass = trim(password_hash(strip_tags(mysqli_real_escape_string($conn, $data['user_pass'])), PASSWORD_DEFAULT));
	$subscription_id = trim(strip_tags(mysqli_real_escape_string($conn, $data['subscription_id'])));
	$billing_cycle = trim(strip_tags(mysqli_real_escape_string($conn, $data['billing_cycle'])));
	$user_cart = json_encode(array('subscription_id' => $subscription_id, 'billing_cycle' => $billing_cycle));

	$session_key = trim(md5($user_email.time()));
	$ip_address = trim($_SERVER['REMOTE_ADDR']);
	$user_agent = trim($_SERVER['HTTP_USER_AGENT']);
	$session_expiry = trim(strtotime("+7 day", time()));

	$session_token = json_encode(array('session_key' => $session_key, 'ip_address' => $ip_address, 'user_agent' => $user_agent, 'session_expiry' => $session_expiry ));

	if($first_name != '' && $last_name != '' && $user_login != '' && $user_email != '' && $user_pass != '') {
		$query = mysqli_query($conn, "INSERT INTO users(user_login, user_pass, user_email, display_name, subscription_id, time_created) VALUES('$user_login', '$user_pass', '$user_email', '$display_name', '$subscription_id', '$time_created')");
		$user_id = mysqli_insert_id($conn);
		$user_meta_query = mysqli_query($conn, "INSERT INTO user_meta(user_id, meta_key, meta_value) VALUES
			('$user_id', 'first_name', '$first_name'), 
			('$user_id', 'last_name', '$last_name'), 
			('$user_id', 'user_phone', ''), 
			('$user_id', 'session_tokens', '$session_token'), 
			('$user_id', 'user_cart', '$user_cart'), 
			('$user_id', 'user_subscription', ''), 
			('$user_id', 'business_name', ''), 
			('$user_id', 'website_url', ''), 
			('$user_id', 'street_address_1', ''), 
			('$user_id', 'street_address_2', ''), 
			('$user_id', 'city', ''), 
			('$user_id', 'state', ''), 
			('$user_id', 'zipcode', ''), 
			('$user_id', 'country', '')
		");
		$_SESSION['email_marketing_user_id'] = $user_id;

		if($query && $user_meta_query) {
			echo json_encode(array('status' => 'success', 'status_code' => '001', 'message' => 'User Successfully Registered', 'session' => array('session_key' => $session_key, 'ip_address' => $ip_address, 'user_agent' => $user_agent, 'session_expiry' => $session_expiry)));
		} else {
			echo json_encode(array('status' => 'error', 'status_code' => '002', 'message' => 'Please Try Again'));
		}
	} else {
		echo json_encode(array('status' => 'error', 'status_code' => '003', 'message' => 'Please Fill Required Fields'));
	}

}

if(isset($data['action']) && $data['action'] == 'user_login') {

	$user_login = trim(strip_tags(mysqli_real_escape_string($conn, $data['user_login'])));
	$user_pass = trim(strip_tags(mysqli_real_escape_string($conn, $data['user_pass'])));

	if($user_login != '' && $user_pass != '') {

		$query = mysqli_query($conn, "SELECT * FROM users WHERE user_login='$user_login' && delete_status='0'");

		if(mysqli_num_rows($query) > 0) {
			$result = mysqli_fetch_assoc($query);
			$user_id = $result['id'];
			if(password_verify($user_pass, $result['user_pass'])) {
				if($result['active_status'] == 1) {
					// session_start();
					$_SESSION['email_marketing_user_id'] = $result['id'];
					$session_token_array = json_decode(user_meta($result['id'], 'session_tokens'), true);

					$session_key = trim(md5($user_login.time()));
					$ip_address = trim($_SERVER['REMOTE_ADDR']);
					$user_agent = trim($_SERVER['HTTP_USER_AGENT']);
					$session_expiry = trim(strtotime("+7 day", time()));

					if($session_token_array != '') {
						$session_token = json_encode( array_merge_recursive($session_token_array, array( array('session_key' => $session_key, 'ip_address' => $ip_address, 'user_agent' => $user_agent, 'session_expiry' => $session_expiry ) )) );
					} else {
						$session_token = json_encode( array( array( 'session_key' => $session_key, 'ip_address' => $ip_address, 'user_agent' => $user_agent, 'session_expiry' => $session_expiry ) ) );
					}

					$session_token_query = mysqli_query($conn, "UPDATE user_meta SET meta_value='$session_token' WHERE user_id='$user_id' && meta_key='session_tokens'");

					echo json_encode(array('status' => 'success', 'status_code' => '001', 'message' => 'User Successfully Registered', 'session' => array('session_key' => $session_key, 'ip_address' => $ip_address, 'user_agent' => $user_agent, 'session_expiry' => $session_expiry)));
				
				} else {
					echo json_encode(array('status' => 'error', 'status_code' => '004', 'message' => 'Please Try Again'));
				}
			} else {
				echo json_encode(array('status' => 'error', 'status_code' => '005', 'message' => 'Incorrect Password'));
			}
		} else {
			echo json_encode(array('status' => 'error', 'status_code' => '002', 'message' => 'Please Try Again'));
		}

	} else {
		echo json_encode(array('status' => 'error', 'status_code' => '003', 'message' => 'Please Fill Required Fields'));
	}

}

if(isset($data['action']) && $data['action'] == 'user_info') {

	$session_key = trim(strip_tags(mysqli_real_escape_string($conn, $data['session_key'])));

	if($session_key != '') {
		
		$check_query = mysqli_query($conn, "SELECT * FROM user_meta WHERE meta_key='session_tokens' && meta_value LIKE '%$session_key%' LIMIT 1");
		if(mysqli_num_rows($check_query) > 0) {
			$check_result = mysqli_fetch_assoc($check_query);
			$session_token = json_decode($check_result['meta_value'], true);
			$key = array_search($session_key, array_column($session_token, 'session_key'));
			if(in_array($session_key, $session_token[$key])) {
				$user_id = $check_result['user_id'];
				// session_start();
				$_SESSION['email_marketing_user_id'] = $user_id;
				$query = mysqli_query($conn, "SELECT id, user_login, user_email, display_name, subscription_id, admin_id, role, type FROM users WHERE id='$user_id' && active_status='1' && delete_status='0'");
				if(mysqli_num_rows($query) > 0) {
					$result = mysqli_fetch_assoc($query);
					$user_meta_query = mysqli_query($conn, "SELECT user_id, meta_key, meta_value FROM user_meta WHERE user_id='$user_id'");
					if(mysqli_num_rows($user_meta_query) > 0) {
						while($user_meta_result = mysqli_fetch_assoc($user_meta_query)) {
							$user_meta[$user_meta_result['meta_key']] = json_decode($user_meta_result['meta_value']);
						}
					}
					echo json_encode( array_merge( array( 'status' => 'success', 'status_code' => '001' ), array_merge( $result, $user_meta ) ) );
				} else {
					echo json_encode( array( 'status' => 'error', 'status_code' => '004' ) );
				}
			} else {
				echo json_encode( array( 'status' => 'error', 'status_code' => '005' ) );
			}
			// }
		} else {
			echo json_encode(array('status' => 'error', 'status_code' => '003'));
		}
	}

}

if(isset($data['action']) && $data['action'] == 'update_cart') {

	$user_id = trim(strip_tags(mysqli_real_escape_string($conn, $data['user_id'])));
	$subscription_id = trim(strip_tags(mysqli_real_escape_string($conn, $data['subscription_id'])));
	$billing_cycle = trim(strip_tags(mysqli_real_escape_string($conn, $data['billing_cycle'])));
	$user_cart = json_encode(array('subscription_id' => $subscription_id, 'billing_cycle' => $billing_cycle));

	if($user_id != '' && $subscription_id != '' && $billing_cycle != '') {
		$query = mysqli_query($conn, "UPDATE user_meta SET meta_value='$user_cart' WHERE meta_key='user_cart' && user_id='$user_id'");
		if($query) {
			echo json_encode(array('status' => 'success', 'status_code' => '001'));
		} else {
			echo json_encode(array('status' => 'error', 'status_code' => '002'));
		}
	} else {
		echo json_encode(array('status' => 'error', 'status_code' => '002'));
	}

}

if(isset($data['action']) && $data['action'] == 'checkout') {

	$user_id = trim(strip_tags(mysqli_real_escape_string($conn, $data['user_id'])));
	$subscription_id = trim(strip_tags(mysqli_real_escape_string($conn, $data['subscription_id'])));
	$billing_cycle = trim(strip_tags(mysqli_real_escape_string($conn, $data['billing_cycle'])));
	$card_holder_name = trim(strip_tags(mysqli_real_escape_string($conn, $data['card_holder_name'])));
	$card_number = trim(strip_tags(mysqli_real_escape_string($conn, $data['card_number'])));
	$card_expiry_month = trim(strip_tags(mysqli_real_escape_string($conn, $data['card_expiry_month'])));
	$card_expriy_year = trim(strip_tags(mysqli_real_escape_string($conn, $data['card_expiry_year'])));
	$card_csv = trim(strip_tags(mysqli_real_escape_string($conn, $data['card_csv'])));
	$payment_method = 'credit card';

	if($user_id != '' && $subscription_id != '' && $billing_cycle != '' && $card_holder_name != '' && $card_number != '' && $card_expiry_month != '' && $card_expriy_year != '' && $card_csv != '') {
		$subscription_query = mysqli_query($conn, "SELECT * FROM subscriptions WHERE id='$subscription_id' && active_status='1' && delete_status='0'");

		if(mysqli_num_rows($subscription_query) > 0) {
			$subscription_meta_query = mysqli_query($conn, "SELECT * FROM subscription_meta WHERE subscription_id='$subscription_id'");
			while($subscription_meta_result = mysqli_fetch_assoc($subscription_meta_query)) {
				$subscription_meta[$subscription_meta_result['meta_key']] = json_decode($subscription_meta_result['meta_value'], true);
			}

			if($billing_cycle == 'monthly') {
				$next_payment_billing = strtotime('+1 month', time());
			} else if($billing_cycle == 'half_year') {
				$next_payment_billing = strtotime('+6 month', time());
			} else if($billing_cycle == 'yearly') {
				$next_payment_billing = strtotime('+1 years', time());
			} else if($billing_cycle == 'bi_year') {
				$next_payment_billing = strtotime('+2 years', time());
			} else if($billing_cycle == 'tri_year') {
				$next_payment_billing = strtotime('+3 years', time());
			}

			$user_subscription = json_encode(
				array(
					'subscription_id' => $subscription_id, 
					'billing_cycle' => $billing_cycle, 
					'payment_status' => '1', 
					'payment_method' => $payment_method, 
					'last_payment_billing' => time(), 
					'next_payment_billing' => $next_payment_billing
				)
			);

			$query = mysqli_query($conn, "UPDATE user_meta SET meta_value= CASE
				WHEN meta_key='user_subscription' THEN '$user_subscription'
				WHEN meta_key='user_cart' THEN '{}'
				ELSE `meta_value`
				END
			WHERE user_id='$user_id'");

			$subscription_transaction_query = mysqli_query($conn, "INSERT INTO subscription_transactions(subscription_id, user_id, price, duration, payment_method, time_created) VALUES('$subscription_id', '$user_id', '{$subscription_meta['price'][$billing_cycle]['price']}', '$billing_cycle', '$payment_method', '$time_created')");

			$user_subscription = mysqli_query($conn, "UPDATE users SET subscription_id='$subscription_id' WHERE id='$user_id'");

			if($query && $subscription_transaction_query && $user_subscription) {
				echo json_encode(array('status' => 'success', 'status_code' => '001'));
			} else {
				echo json_encode(array('status' => 'error', 'status_code' => '002'));
			}
		} else {
			echo json_encode(array('status' => 'error', 'status_code' => '002'));
		}
	} else {
		echo json_encode(array('status' => 'error', 'status_code' => '003'));
	}
}

if(isset($data['action']) && $data['action'] == 'get_accounts') {

	$user_id = trim(strip_tags(mysqli_real_escape_string($conn, $data['user_id'])));

	if(isset($data['staff_id']) && $data['staff_id'] != '') {
		$staff_id = trim(strip_tags(mysqli_real_escape_string($conn, $data['staff_id'])));
	} else {
		$staff_id = '';
	}

	if($user_id != '') {
		if($staff_id == '') {
			$query = mysqli_query($conn, "SELECT id, user_id, admin_id, account_title, account_email, verified_status, active_status FROM accounts WHERE (user_id='$user_id' || admin_id='$user_id') && delete_status='0'");
		} else {
			$query = mysqli_query($conn, "SELECT id, user_id, admin_id, account_title, account_email, verified_status, active_status FROM accounts WHERE user_id='$staff_id' && admin_id='$user_id' && delete_status='0'");
		}

		$result = array();

		if(mysqli_num_rows($query) > 0) {
			$i = 0;
			while($row = mysqli_fetch_assoc($query)) {
				$result[$i] = array(
					'id' => $row['id'], 
					'user_id' => $row['user_id'], 
					'admin_id' => $row['admin_id'], 
					'staff_name' => display_name($row['user_id']), 
					'account_title' => $row['account_title'], 
					'account_email' => $row['account_email'], 
					'verified_status' => $row['verified_status'], 
					'active_status' => $row['active_status']
				);
				$i++;
			}
			$records = array('display_records' => $result);
			echo json_encode(array_merge(array('status' => 'success', 'status_code' => '001', 'total_record' => mysqli_num_rows($query)), $records));
		} else {
			echo json_encode(array('status' => 'error', 'status_code' => '004', 'total_record' => mysqli_num_rows($query)));
		}

	} else {
		echo json_encode(array('status' => 'error', 'status_code' => '002'));
	}

}

if(isset($data['action']) && $data['action'] == 'single_account') {

	$account_id = trim(strip_tags(mysqli_real_escape_string($conn, $data['account_id'])));

	$user_id = trim(strip_tags(mysqli_real_escape_string($conn, $data['user_id'])));

	if($account_id != '' && $user_id != '') {
		$query = mysqli_query($conn, "SELECT id, user_id, admin_id, account_title, account_email, verified_status, active_status FROM accounts WHERE id='$account_id' && (user_id='$user_id' || admin_id='$user_id') && delete_status='0'");

		$result = array();

		if(mysqli_num_rows($query) > 0) {
			$result = mysqli_fetch_assoc($query);
			$records = array('display_records' => $result);
			echo json_encode(array_merge(array('status' => 'success', 'status_code' => '001', 'total_record' => mysqli_num_rows($query)), $records));
		} else {
			echo json_encode(array('status' => 'error', 'status_code' => '004', 'total_record' => mysqli_num_rows($query)));
		}

	} else {
		echo json_encode(array('status' => 'error', 'status_code' => '002'));
	}

}

if(isset($data['action']) && $data['action'] == 'new_account') {

	$user_id = trim(strip_tags(mysqli_real_escape_string($conn, $data['user_id'])));
	$staff_id = trim(strip_tags(mysqli_real_escape_string($conn, $data['staff_id'])));
	$account_title = trim(strip_tags(mysqli_real_escape_string($conn, $data['account_title'])));
	$account_email = trim(strip_tags(mysqli_real_escape_string($conn, $data['account_email'])));
	$account_password = trim(strip_tags(mysqli_real_escape_string($conn, $data['account_password'])));

	if($user_id != '' && $account_title != '' && $account_email != '' && $account_password != '') {

		$admin_id_query = mysqli_query($conn, "SELECT admin_id FROM users WHERE id='$user_id' && active_status='1' && delete_status='0'");
		if(mysqli_num_rows($admin_id_query) > 0) {
			$admin_id_result = mysqli_fetch_assoc($admin_id_query);
			if(is_null($admin_id_result['admin_id'])) {
				$admin_id = $user_id;
			} else {
				$admin_id = $admin_id_result['admin_id'];
			}
		} else {
			$admin_id = $user_id;
		}

		$user_meta_query = mysqli_query($conn, "SELECT subscription_id FROM users WHERE id='$admin_id' && active_status='1' && delete_status='0'");

		if(mysqli_num_rows($user_meta_query) > 0) {
			$user_meta_result = mysqli_fetch_assoc($user_meta_query);
			$subscription_id = $user_meta_result['subscription_id'];

			$user_subscription_transaction = json_decode(user_meta($admin_id, 'user_subscription'), true);

			if(time() < $user_subscription_transaction['next_payment_billing']) {

				$subscription_query = mysqli_query($conn, "SELECT * FROM subscriptions WHERE id='$subscription_id' && active_status='1' && delete_status='0'");
				if(mysqli_num_rows($subscription_query) > 0) {
					$subscription_meta_query = mysqli_query($conn, "SELECT meta_value FROM subscription_meta WHERE subscription_id='$subscription_id' && meta_key='features'");
					if(mysqli_num_rows($subscription_meta_query) > 0) {

						$subscription_meta_ = mysqli_fetch_assoc($subscription_meta_query);
						$subscription_meta_result = json_decode($subscription_meta_['meta_value'], true);

						if($staff_id == '') {
							$total_accounts = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM accounts WHERE user_id='$admin_id' && delete_status='0'"));
						} else {
							$total_accounts = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM accounts WHERE admin_id='$admin_id' && user_id='$staff_id' && delete_status='0'"));
						}

						if($total_accounts < $subscription_meta_result['email_accounts']['limit']) {

							if($staff_id == '') {
								$query = mysqli_query($conn, "INSERT INTO accounts(user_id, admin_id, account_title, account_email, account_password, time_created) VALUES('$user_id', '$admin_id', '$account_title', '$account_email', '$account_password', '$time_created')");
							} else {
								$query = mysqli_query($conn, "INSERT INTO accounts(user_id, admin_id, account_title, account_email, account_password, time_created) VALUES('$staff_id', '$admin_id', '$account_title', '$account_email', '$account_password', '$time_created')");
							}

							$account_id = mysqli_insert_id($conn);

							$verify_code = md5($account_email.$time_created);

							$verify_query = mysqli_query($conn, "INSERT INTO verification(user_id, verify_type, verify_item_id, verify_method, verify_code, time_created) VALUES('$user_id', '1', '$account_id', 'email', '$verify_code', '$time_created')");

							if($query && $verify_code) {
								$to = $account_email;
								$account_host = explode('@', $account_email);
								$message = "
								<p>You have requested to add ".$account_email." to WireCoder Email Marketing account.
								Confirmation code: ".$verify_code."</p>
								
								<p>Before you can send mail and receive from ".$account_email." using your WireCoder Email Marketing
								account, please click the link below
								to confirm your request:</p>
								
								<a href='".$api_url."verify/?action=verify&type=1&associate_id=".$user_id."&verify_item=".$account_id."&verify_type=1&verify_method=email&verify_code=".$verify_code."'>http://localhost/email-marketing/api/verify/?action=verify&type=1&associate_id=".$user_id."&verify_item=".$account_id."&verify_type=1&verify_method=email&verify_code=".$verify_code."</a>
								
								<p>If you click the link and it appears to be broken, please copy and
								paste it into a new browser window. If you aren't able to access the
								link, please log in to your WireCoder Email Marketing account and click 'Accounts' from 
								the Sidebar of any page.
								Open the 'Accounts' tab and locate the email address you'd like to add
								in the'Send mail as:' section. Then, click 'Verify' and enter your
								confirmation code: ".$verify_code."</p>
								
								<p>Thanks for using WireCoder Email Marketing!</p>
								
								<p>Yours sincerely,</p>
								
								<p>The WireCoder Email Marketing Team</p>
								
								<p>If you did not make this request or you don't want to add this email
								address to your WireCoder Email Marketing account, no further action is required.
								You cannot send messages using your email
								address unless you confirm the request by clicking the link above. If
								you accidentally clicked the link but you do not want to allow
								anyone to send messages using your address,
								click this link to cancel this verification:</p>
								
								<a href='".$api_url."verify/?action=verify&type=0&associate_id=".$user_id."&verify_item=".$account_id."&verify_type=1&verify_method=email'>http://localhost/email-marketing/api/verify/?action=verify&type=0&associate_id=".$user_id."&verify_item=".$account_id."&verify_type=1&verify_method=email</a>
								
								<p>To learn more about why you might have received this message, please
								visit: .</p>
								
								<p>Please do not respond to this message. If you'd like to contact the
								WireCoder Email Marketing Team, please Visit <a href='#'>Contact Us</a>.</p>";
								$SMTP = new SMTP('wirecoder.com', 'no-reply@wirecoder.com', '7S@!fullah');
								$subject = '['.$account_host[1].'] Client configuration settings for '.$account_email;
								$SMTP->addToAddress($to);
								$SMTP->addSubject($subject);
								$SMTP->addMessage($message);
								$SMTP->sendNormalEmail(1);
								echo json_encode(array('status' => 'success', 'status_code' => '001'));
							} else {
								echo json_encode(array('status' => 'error', 'status_code' => '002'));
							}

						} else {
							echo json_encode(array('status' => 'error', 'status_code' => '002', 'status_subcode' => '2001'));
						}

					} else {
						echo json_encode(array('status' => 'error', 'status_code' => '002'));
					}
				} else {
					echo json_encode(array('status' => 'error', 'status_code' => '002'));
				}
			} else {
				echo json_encode(array('status' => 'error', 'status_code' => '2002', 'status_subcode' => '2002'));
			}

		} else {
			echo json_encode(array('status' => 'error', 'status_code' => '002'));
		}

	} else {
		echo json_encode(array('status' => 'error', 'status_code' => '003'));
	}

}

if(isset($data['action']) && $data['action'] == 'get_total_emails') {

	require_once('classes/imap_original.php');

	$user_id = trim(strip_tags(mysqli_real_escape_string($conn, $data['user_id'])));
	$account_id = trim(strip_tags(mysqli_real_escape_string($conn, $data['account_id'])));

	if($user_id != '' && $user_id != 0 && $account_id != '' && $account_id != 0) {

		$query = mysqli_query($conn, "SELECT * FROM accounts WHERE id='$account_id' && user_id='$user_id' && verified_status='1' && active_status='1' && delete_status='0'");
		if(mysqli_num_rows($query) > 0) {
			$result = mysqli_fetch_assoc($query);
			$user_id = $result['user_id'];
			$account_host = explode('@', $result['account_email']);
			$mailbox = array(
				'inbox' => 'INBOX', 
				'archive' => 'INBOX.Archive', 
				'trash' => 'INBOX.Trash', 
				'sent' => 'INBOX.Sent', 
				'drafts' => 'INBOX.Drafts', 
				'spam' => 'INBOX.spam'
			);

			$output = array();
			$i = 0;

			foreach($mailbox as $mailbox_key => $mailbox_value) {

				$connection = imap_open('{'.$account_host[1].':993/ssl}'.$mailbox_value, $result['account_email'], $result['account_password']);
				$total_msg = imap_num_msg($connection);
				$output[$mailbox_key] = array('inbox' => $mailbox_value, 'total_msg' => $total_msg);

			}

			$output = array_merge(array('status' => 'success', 'status_code' => '001'), array('display_record' => $output));
			echo json_encode($output);
		} else {
			echo json_encode(array('status' => 'error', 'status_code' => '002'));
		}

	} else {
		echo json_encode(array('status' => 'error', 'status_code' => '003'));
	}

}

if(isset($data['action']) && $data['action'] == 'downloading_emails') {
	
	require_once('classes/imap_original.php');

	$account_id = trim(strip_tags(mysqli_real_escape_string($conn, $data['account_id'])));
	$user_id = trim(strip_tags(mysqli_real_escape_string($conn, $data['user_id'])));
	$mailbox = trim(strip_tags(mysqli_real_escape_string($conn, $data['mailbox'])));
	$mailbox_key = trim(strip_tags(mysqli_real_escape_string($conn, $data['mailbox_key'])));
	echo json_encode(array('status' => 'success', 'status_code' => '001'));
	die();

	if($account_id != '' && $user_id != '' && $mailbox != '') {
		
		$admin_id_query = mysqli_query($conn, "SELECT admin_id FROM users WHERE id='$user_id' && active_status='1' && delete_status='0'");
		if(mysqli_num_rows($admin_id_query) > 0) {
			$admin_id_result = mysqli_fetch_assoc($admin_id_query);
			if(is_null($admin_id_result['admin_id'])) {
				$admin_id = $user_id;
			} else {
				$admin_id = $admin_id_result['admin_id'];
			}
		} else {
			$admin_id = $user_id;
		}

		$query = mysqli_query($conn, "SELECT * FROM accounts WHERE id='$account_id' && (user_id='$user_id' || admin_id='$user_id') && active_status='1' && delete_status='0'");

		if(mysqli_num_rows($query) > 0) {
			$result = mysqli_fetch_assoc($query);

			$email = new Imap('attachments/'.$result['account_email']);
			$account_host = explode('@', $result['account_email']);

			$i = 1;

			$total_downloaded_messages = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM inbox WHERE account_id='$account_id' && mailbox='$mailbox_key' && type='0'"));
			$connection = $email->connect('{'.$account_host[1].':993/ssl}'.$mailbox, $result['account_email'], $result['account_password']);
			$email->setLimit(1);
			$inbox = $email->getMessages('html', 'desc', $data['current_msg'], $data['total_msg']);
			
			echo '<pre>';
			print_r($inbox);
			echo '</pre>';
			if($inbox) {
				
				
				foreach ($inbox as $v) {

					$subject = trim($v['subject']);
					$udate = trim($v['date']);
					$message_id = trim($v['message_id']);

					$to_address = '';
					$date = '';
					$size = '';
					$msg_no = '';
					$recent = '';
					$flagged = '';
					$answered = '';
					$deleted = '';
					$seen_status = '';
					$draft = '';

					foreach($v['message_number'] as $key => $value) {
						if($key == 'to') {
							$to_address = trim($value);
						} else if($key == 'date') {
							$date = trim($value);
						} else if($key == 'size') {
							$size = trim($value);
						} else if($key == 'msgno') {
							$msg_no = trim($value);
						} else if($key == 'recent') {
							$recent = trim($value);
						} else if($key == 'flagged') {
							$flagged = trim($value);
						} else if($key == 'answered') {
							$answered = trim($value);
						} else if($key == 'deleted') {
							$deleted = trim($value);
						} else if($key == 'seen') {
							$seen_status = trim($value);
						} else if($key == 'draft') {
							$draft = trim($value);
						}
					}

					$uid = trim($v['uid']);
					$references = trim($v['references']);
					if(isset($v['from'][0]['address'])) {
						$from_address = trim($v['from'][0]['address']);
					} else {
						$from_address = '';
					}
					if(isset($v['from'][0]['name'])) {
						$from_name = trim($v['from'][0]['name']);
					} else {
						$from_name = '';
					}
					if(isset($v['cc'][0]['address'])) {
						$cc_address = trim($v['cc'][0]['address']);
					} else {
						$cc_address = '';
					}
					$message = htmlentities(htmlspecialchars($v['message']));

					$inbox_query = mysqli_query($conn, "INSERT INTO inbox(user_id, admin_id, account_id, uid, mailbox, from_address, to_address, is_starred, is_important, seen_status, subject, content, type, udate, time_created) VALUES('{$user_id}', {$admin_id}, '{$account_id}', '{$uid}', '{$mailbox_key}', '{$from_address}', '{$to_address}', '0', '{$flagged}', '{$seen_status}', '{$subject}', '{$message}', '0', '{$udate}', '{$time_created}')");

					$inbox_id = mysqli_insert_id($conn);

					$inbox_meta_query = mysqli_query($conn, "INSERT INTO inbox_meta(inbox_id, meta_key, meta_value) VALUES
						('$inbox_id', 'message_id', '$message_id'), 
						('$inbox_id', 'from_name', '$from_name'), 
						('$inbox_id', 'cc_address', '$cc_address'), 
						('$inbox_id', 'date', '$date'), 
						('$inbox_id', 'size', '$size')
					");

					if(sizeof($v['attachments']) > 0) {
						foreach($v['attachments'] as $attachments) {
							$attachment_url = $home_url.'api/attachments/'.$result['account_email'].'/'.$attachments;
							$attachment_type = substr(strrchr($attachment_url, '.'), 1);
							$attachment_query = mysqli_query($conn, "INSERT INTO inbox_attachments(inbox_id, attachment_url, attachment_type, time_created) VALUE('$inbox_id', '$attachment_url', '$attachment_type', '$time_created')");
						}
					}
				}
				
				
			}
			echo json_encode(array('status' => 'success', 'status_code' => '001'));
			
		}
	}

}

if(isset($data['action']) && $data['action'] == 'edit_account') {

	if(isset($data['staff_id'])) {
		$admin_id = trim(strip_tags(mysqli_real_escape_string($conn, $data['user_id'])));
		$staff_id = trim(strip_tags(mysqli_real_escape_string($conn, $data['staff_id'])));
	} else {
		$admin_id = trim(strip_tags(mysqli_real_escape_string($conn, $data['user_id'])));
		$staff_id = trim(strip_tags(mysqli_real_escape_string($conn, $data['user_id'])));
	}

	$account_id = trim(strip_tags(mysqli_real_escape_string($conn, $data['account_id'])));
	$account_title = trim(strip_tags(mysqli_real_escape_string($conn, $data['account_title'])));
	$account_email = trim(strip_tags(mysqli_real_escape_string($conn, $data['account_email'])));
	$account_password = trim(strip_tags(mysqli_real_escape_string($conn, $data['account_password'])));
	$active_status = trim(strip_tags(mysqli_real_escape_string($conn, $data['active_status'])));

	if($account_id != '' && $admin_id != '' && $staff_id != '' && $account_title != '' && $account_email != '' && $active_status != '') {

		if($account_password == '') {
			$query = mysqli_query($conn, "UPDATE accounts SET account_title='$account_title', account_email='$account_email', active_status='$active_status' WHERE id='$account_id'");
		} else {
			$query = mysqli_query($conn, "UPDATE accounts SET account_title='$account_title', account_email='$account_email', account_password='$account_password', active_status='$active_status' WHERE id='$account_id'");
		}

		if($query) {
			echo json_encode(array('status' => 'success', 'status_code' => '001'));
		} else {
			echo json_encode(array('status' => 'error', 'status_code' => '002'));
		}

	} else {
		echo json_encode(array('status' => 'error', 'status_code' => '003'));
	}

}

if(isset($data['action']) && $data['action'] == 'delete_account') {

	$account_id = trim(strip_tags(mysqli_real_escape_string($conn, $data['account_id'])));

	if($account_id != '' && $account_id != 0) {
		$query = mysqli_query($conn, "UPDATE accounts SET delete_status='1' WHERE id='$account_id'");

		if($query) {
			echo json_encode(array('status' => 'success', 'status_code' => '001'));
		} else {
			echo json_encode(array('status' => 'error', 'status_code' => '002'));
		}
	} else {
		echo json_encode(array('status' => 'error', '003'));
	}

}

if(isset($data['action']) && $data['action'] == 'get_inbox') {

	$mailbox = trim(strip_tags(mysqli_real_escape_string($conn, $data['mailbox'])));
	$account_id = trim(strip_tags(mysqli_real_escape_string($conn, $data['account_id'])));
	$user_id = trim(strip_tags(mysqli_real_escape_string($conn, $data['user_id'])));
	$page = trim(strip_tags(mysqli_real_escape_string($conn, $data['page'])));
	$per_page_record = trim(strip_tags(mysqli_real_escape_string($conn, $data['per_page_record'])));

	if($user_id != '') {

		if(isset($data['q']) && $data['q'] != '') {
			
			$q = trim(strip_tags(mysqli_real_escape_string($conn, $data['q'])));

			$total_record = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM inbox WHERE account_id='$account_id' && mailbox='$mailbox' && parent_id is NULL && delete_status='0'"));
			$total_pages = ceil($total_record / $per_page_record);
			$offset = ($page - 1) * $per_page_record;
			$query = mysqli_query($conn, "SELECT i.id, i.account_id, i.uid, i.mailbox, i.from_address, i.to_address, i.is_starred, i.is_important, i.seen_status, i.subject, i.content, i.udate, i.time_created FROM inbox AS i LEFT JOIN inbox_meta AS im ON i.id=im.inbox_id WHERE ( ( i.from_address LIKE '%$q%' ) || ( i.to_address LIKE '%$q%' ) || ( i.subject LIKE '%$q%' ) || ( i.content LIKE '%$q%' ) || (im.meta_key='from_name' && im.meta_value LIKE '%$q%' ) || (im.meta_key='cc_address' && im.meta_value LIKE '%$q%') ) && i.account_id='$account_id' && i.mailbox='$mailbox' && i.parent_id is NULL && i.delete_status='0' && im.meta_key='cc_address' ORDER BY i.udate DESC");
			
		} else {

			$total_record = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM inbox WHERE account_id='$account_id' && mailbox='$mailbox' && parent_id is NULL && delete_status='0'"));
			$total_pages = ceil($total_record / $per_page_record);
			$offset = ($page - 1) * $per_page_record;
			$query = mysqli_query($conn, "SELECT id, account_id, uid, mailbox, from_address, to_address, is_starred, is_important, seen_status, subject, content, udate, time_created FROM inbox WHERE account_id='$account_id' && mailbox='$mailbox' && parent_id is NULL && delete_status='0' ORDER BY udate DESC LIMIT $offset, $per_page_record");
		
		}

		$result = array();

		if(mysqli_num_rows($query) > 0) {
			$i = 0;
			while($row = mysqli_fetch_assoc($query)) {
				$inbox_id = $row['id'];
				$result[$i] = array(
					'id' => $row['id'], 
					'account_id' => $row['account_id'], 
					'uid' => $row['uid'],
					'mailbox' => $row['mailbox'],
					'from_address' => $row['from_address'],
					'to_address' => $row['to_address'],
					'is_starred' => $row['is_starred'],
					'is_important' => $row['is_important'],
					'seen_status' => $row['seen_status'],
					'subject' => $row['subject'],
					'content' => html_entity_decode(htmlspecialchars_decode($row['content'])),
					'udate' => time_ago($row['udate']),
					'time_created' => $row['time_created']
				);
				$meta_query = mysqli_query($conn, "SELECT * FROM inbox_meta WHERE inbox_id='$inbox_id'");
				if(mysqli_num_rows($meta_query) > 0) {
					while($meta_result = mysqli_fetch_assoc($meta_query)) {
						$result[$i] = array_merge(array($meta_result['meta_key'] => $meta_result['meta_value']), $result[$i]);
					}
				}
				$i++;
				
			}
			$records = array('display_records' => $result);
			echo json_encode(array_merge(array('status' => 'success', 'status_code' => '001', 'total_record' => mysqli_num_rows($query), 'total_pages' => $total_pages, 'offset' => $offset), $records));
		} else {
			echo json_encode(array('status' => 'error', 'status_code' => '004', 'total_record' => mysqli_num_rows($query)));
		}

	} else {
		echo json_encode(array('status' => 'error', 'status_code' => '002'));
	}

}

if(isset($data['action']) && $data['action'] == 'get_starred') {

	$mailbox = trim(strip_tags(mysqli_real_escape_string($conn, $data['mailbox'])));
	$account_id = trim(strip_tags(mysqli_real_escape_string($conn, $data['account_id'])));
	$user_id = trim(strip_tags(mysqli_real_escape_string($conn, $data['user_id'])));
	$page = trim(strip_tags(mysqli_real_escape_string($conn, $data['page'])));
	$per_page_record = trim(strip_tags(mysqli_real_escape_string($conn, $data['per_page_record'])));

	if($user_id != '') {
		$total_record = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM inbox WHERE account_id='$account_id' && mailbox='$mailbox' && is_starred='1' && parent_id is NULL && delete_status='0'"));
		$total_pages = ceil($total_record / $per_page_record);
		$offset = ($page - 1) * $per_page_record;
		$query = mysqli_query($conn, "SELECT id, account_id, uid, mailbox, from_address, to_address, is_starred, is_important, seen_status, subject, content, udate, time_created FROM inbox WHERE account_id='$account_id' && mailbox='$mailbox' && is_starred='1' && parent_id is NULL && delete_status='0' ORDER BY udate DESC LIMIT $offset, $per_page_record");

		$result = array();

		if(mysqli_num_rows($query) > 0) {
			while($row = mysqli_fetch_assoc($query)) {
				$inbox_id = $row['id'];
				$result['id_'.$row['id']] = array(
					'id' => $row['id'], 
					'account_id' => $row['account_id'], 
					'uid' => $row['uid'],
					'mailbox' => $row['mailbox'],
					'from_address' => $row['from_address'],
					'to_address' => $row['to_address'],
					'is_starred' => $row['is_starred'],
					'is_important' => $row['is_important'],
					'seen_status' => $row['seen_status'],
					'subject' => $row['subject'],
					'content' => html_entity_decode(htmlspecialchars_decode($row['content'])),
					'udate' => time_ago($row['udate']),
					'time_created' => $row['time_created']
				);
				$meta_query = mysqli_query($conn, "SELECT * FROM inbox_meta WHERE inbox_id='$inbox_id'");
				if(mysqli_num_rows($meta_query) > 0) {
					while($meta_result = mysqli_fetch_assoc($meta_query)) {
						$result['id_'.$row['id']] = array_merge(array($meta_result['meta_key'] => $meta_result['meta_value']), $result['id_'.$row['id']]);
					}
				}
				
			}
			$records = array('display_records' => $result);
			echo json_encode(array_merge(array('status' => 'success', 'status_code' => '001', 'total_record' => $total_record, 'total_pages' => $total_pages, 'offset' => $offset), $records));
		} else {
			echo json_encode(array('status' => 'error', 'status_code' => '004', 'total_record' => $total_record));
		}

	} else {
		echo json_encode(array('status' => 'error', 'status_code' => '002'));
	}

}

if(isset($data['action']) && $data['action'] == 'get_important') {

	$mailbox = trim(strip_tags(mysqli_real_escape_string($conn, $data['mailbox'])));
	$account_id = trim(strip_tags(mysqli_real_escape_string($conn, $data['account_id'])));
	$user_id = trim(strip_tags(mysqli_real_escape_string($conn, $data['user_id'])));
	$page = trim(strip_tags(mysqli_real_escape_string($conn, $data['page'])));
	$per_page_record = trim(strip_tags(mysqli_real_escape_string($conn, $data['per_page_record'])));

	if($user_id != '') {
		$total_record = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM inbox WHERE account_id='$account_id' && mailbox='$mailbox' && is_important='1' && parent_id is NULL && delete_status='0'"));
		$total_pages = ceil($total_record / $per_page_record);
		$offset = ($page - 1) * $per_page_record;
		$query = mysqli_query($conn, "SELECT id, account_id, uid, mailbox, from_address, to_address, is_starred, is_important, seen_status, subject, content, udate, time_created FROM inbox WHERE account_id='$account_id' && mailbox='$mailbox' && is_important='1' && parent_id is NULL && delete_status='0' ORDER BY udate DESC LIMIT $offset, $per_page_record");

		$result = array();

		if(mysqli_num_rows($query) > 0) {
			while($row = mysqli_fetch_assoc($query)) {
				$inbox_id = $row['id'];
				$result['id_'.$row['id']] = array(
					'id' => $row['id'], 
					'account_id' => $row['account_id'], 
					'uid' => $row['uid'],
					'mailbox' => $row['mailbox'],
					'from_address' => $row['from_address'],
					'to_address' => $row['to_address'],
					'is_starred' => $row['is_starred'],
					'is_important' => $row['is_important'],
					'seen_status' => $row['seen_status'],
					'subject' => $row['subject'],
					'content' => html_entity_decode(htmlspecialchars_decode($row['content'])),
					'udate' => time_ago($row['udate']),
					'time_created' => $row['time_created']
				);
				$meta_query = mysqli_query($conn, "SELECT * FROM inbox_meta WHERE inbox_id='$inbox_id'");
				if(mysqli_num_rows($meta_query) > 0) {
					while($meta_result = mysqli_fetch_assoc($meta_query)) {
						$result['id_'.$row['id']] = array_merge(array($meta_result['meta_key'] => $meta_result['meta_value']), $result['id_'.$row['id']]);
					}
				}
				
			}
			$records = array('display_records' => $result);
			echo json_encode(array_merge(array('status' => 'success', 'status_code' => '001', 'total_record' => $total_record, 'total_pages' => $total_pages, 'offset' => $offset), $records));
		} else {
			echo json_encode(array('status' => 'error', 'status_code' => '004', 'total_record' => $total_record));
		}

	} else {
		echo json_encode(array('status' => 'error', 'status_code' => '002'));
	}

}

if(isset($data['action']) && $data['action'] == 'get_message') {

	$id = trim(strip_tags(mysqli_real_escape_string($conn, $data['id'])));
	$user_id = trim(strip_tags(mysqli_real_escape_string($conn, $data['user_id'])));

	if($id != '' && $id != 0 && $user_id != '' && $user_id != 0) {
		$query = mysqli_query($conn, "SELECT id, account_id, uid, mailbox, from_address, to_address, is_starred, is_important, seen_status, subject, content, udate, time_created FROM inbox WHERE id='$id' && delete_status='0'");
		$mark_read_query = mysqli_query($conn, "UPDATE inbox SET seen_status='1' WHERE id='$id'");

		$result = array();

		if(mysqli_num_rows($query) > 0) {
			$row = mysqli_fetch_assoc($query);

			$inbox_id = $row['id'];
			$result = array(
				'id' => $row['id'], 
				'account_id' => $row['account_id'], 
				'uid' => $row['uid'],
				'mailbox' => $row['mailbox'],
				'from_address' => $row['from_address'],
				'to_address' => $row['to_address'],
				'is_starred' => $row['is_starred'],
				'is_important' => $row['is_important'],
				'seen_status' => $row['seen_status'],
				'subject' => $row['subject'],
				'content' => html_entity_decode(htmlspecialchars_decode($row['content'])),
				'udate' => date('d-M-Y h:i A', $row['udate']),
				'time_created' => $row['time_created']
			);
			$meta_query = mysqli_query($conn, "SELECT * FROM inbox_meta WHERE inbox_id='$inbox_id'");
			if(mysqli_num_rows($meta_query) > 0) {
				while($meta_result = mysqli_fetch_assoc($meta_query)) {
					$result = array_merge(array($meta_result['meta_key'] => $meta_result['meta_value']), $result);
				}
			}

			$attachment_query = mysqli_query($conn, "SELECT attachment_name, attachment_url, attachment_type, attachment_size FROM inbox_attachments WHERE inbox_id='$id' && active_status='1' && delete_status='0'");
			if(mysqli_num_rows($attachment_query) > 0) {
				$i = 0;
				while($attachment_result = mysqli_fetch_assoc($attachment_query)) {
					$attachments_result[$i] = array('attachment_name' => get_file_name($attachment_result['attachment_url']), 'attachment_url' => $attachment_result['attachment_url'], 'attachment_type' => $attachment_result['attachment_type'], 'attachment_size' => file_size($attachment_result['attachment_url']));
					$i++;
				}
			} else {
				$attachments_result = array();
			}

			$result = array_merge($result, array('attachments' => array('total_records' => mysqli_num_rows($attachment_query), 'attachment_records' => $attachments_result)));

			$records = array('display_records' => $result);
			echo json_encode(array_merge(array('status' => 'success', 'status_code' => '001', 'total_record' => mysqli_num_rows($query)), $records));
		} else {
			echo json_encode(array('status' => 'error', 'status_code' => '004', 'total_record' => mysqli_num_rows($query)));
		}

	} else {
		echo json_encode(array('status' => 'error', 'status_code' => '002'));
	}

}

if(isset($data['action']) && $data['action'] == 'starred_label') {

	$id = trim(strip_tags(mysqli_real_escape_string($conn, $data['id'])));
	$method = trim(strip_tags(mysqli_real_escape_string($conn, $data['method'])));

	if($method == 'add_starred') {
		$is_starred = 1;
	} else {
		$is_starred = 0;
	}

	if($id != '' && $id != 0 && $method != '') {
		$query = mysqli_query($conn, "UPDATE inbox SET is_starred='$is_starred' WHERE id='$id'");
		if($query) {
			if($query) {
				echo json_encode(array('status' => 'success', 'status_code' => '001'));
			} else {
				echo json_encode(array('status' => 'error', 'status_code' => '002'));
			}
		}
	}

}

if(isset($data['action']) && $data['action'] == 'important_label') {
	
	$id = trim(strip_tags(mysqli_real_escape_string($conn, $data['id'])));
	$method = trim(strip_tags(mysqli_real_escape_string($conn, $data['method'])));

	if($method == 'add_important') {
		$is_important = 1;
	} else {
		$is_important = 0;
	}

	if($id != '' && $id != 0 && $method != '') {
		$query = mysqli_query($conn, "UPDATE inbox SET is_important='$is_important' WHERE id='$id'");
		if($query) {
			if($query) {
				echo json_encode(array('status' => 'success', 'status_code' => '001'));
			} else {
				echo json_encode(array('status' => 'error', 'status_code' => '002'));
			}
		}
	}

}

if(isset($data['action']) && $data['action'] == 'move_mailbox') {
	
	$mailbox = trim(strip_tags(mysqli_real_escape_string($conn, $data['mailbox'])));
	$method = trim(strip_tags(mysqli_real_escape_string($conn, $data['method'])));
	$id = trim(strip_tags(mysqli_real_escape_string($conn, $data['id'])));

	if($id != '' && $id != 0 && $mailbox != '' && $method != '') {
		if($method == 'add_trash') {
			$query = mysqli_query($conn, "UPDATE inbox SET delete_status= CASE
				WHEN delete_status='1' THEN '0'
				WHEN delete_status='0' THEN '1'
				ELSE `delete_status`
				END
			WHERE id='$id'");
		} else if($method == 'add_read') {
			$query = mysqli_query($conn, "UPDATE inbox SET seen_status= CASE
				WHEN seen_status='1' THEN '0'
				WHEN seen_status='0' THEN '1'
				ELSE `seen_status`
				END
			WHERE id='$id'");
		} else {
			$query = mysqli_query($conn, "UPDATE inbox SET mailbox='$mailbox' WHERE id='$id'");
		}
		if($query) {
			echo json_encode(array('status' => 'success', 'status_code' => '001'));
		} else {
			echo json_encode(array('status' => 'error', 'status_code' => '002'));
		}
	}

}

if(isset($data['action']) && $data['action'] == 'bulk_action_move_mailbox') {
	
	$mailbox = trim(strip_tags(mysqli_real_escape_string($conn, $data['mailbox'])));
	$method = trim(strip_tags(mysqli_real_escape_string($conn, $data['method'])));
	$id = implode(',', $data['id']);

	if($id != '' && $id != 0 && $mailbox != '' && $method != '') {
		if($method == 'add_trash') {
			$query = mysqli_query($conn, "UPDATE inbox SET delete_status= CASE
				WHEN delete_status='1' THEN '0'
				WHEN delete_status='0' THEN '1'
				ELSE `delete_status`
				END
			WHERE id IN ($id)");
		} else if($method == 'add_read') {
			$query = mysqli_query($conn, "UPDATE inbox SET seen_status= CASE
				WHEN seen_status='1' THEN '0'
				WHEN seen_status='0' THEN '1'
				ELSE `seen_status`
				END
			WHERE id IN ($id)");
		} else if($method == 'add_important') {
			$query = mysqli_query($conn, "UPDATE inbox SET is_important= CASE
				WHEN is_important='1' THEN '0'
				WHEN is_important='0' THEN '1'
				ELSE `is_important`
				END
			WHERE id IN ($id)");
		} else if($method == 'add_starred') {
			$query = mysqli_query($conn, "UPDATE inbox SET is_starred= CASE
				WHEN is_starred='1' THEN '0'
				WHEN is_starred='0' THEN '1'
				ELSE `is_starred`
				END
			WHERE id IN ($id)");
		} else {
			$query = mysqli_query($conn, "UPDATE inbox SET mailbox='$mailbox' WHERE id IN ($id)");
		}
		if($query) {
			echo json_encode(array('status' => 'success', 'status_code' => '001'));
		} else {
			echo json_encode(array('status' => 'error', 'status_code' => '002'));
		}
	}

}

if(isset($data['action']) && $data['action'] == 'get_urgent_email_info') {
	
	$user_id = trim(strip_tags(mysqli_real_escape_string($conn, $data['user_id'])));
	$account_id = trim(strip_tags(mysqli_real_escape_string($conn, $data['account_id'])));
	$admin_id = trim(strip_tags(mysqli_real_escape_string($conn, admin_id($user_id))));
	$user_role = json_decode(user_meta($user_id, 'user_role'), true);

	if($user_id != '' && $user_id != 0 && $account_id != '' && $account_id != 0) {

		$user_meta_query = mysqli_query($conn, "SELECT subscription_id FROM users WHERE id='$admin_id' && active_status='1' && delete_status='0'");

		if(mysqli_num_rows($user_meta_query) > 0) {
			$user_meta_result = mysqli_fetch_assoc($user_meta_query);
			$subscription_id = $user_meta_result['subscription_id'];

			$subscription_query = mysqli_query($conn, "SELECT * FROM subscriptions WHERE id='$subscription_id' && active_status='1' && delete_status='0'");
			if(mysqli_num_rows($subscription_query) > 0) {
				$subscription_meta_query = mysqli_query($conn, "SELECT meta_value FROM subscription_meta WHERE subscription_id='$subscription_id' && meta_key='features'");
				if(mysqli_num_rows($subscription_meta_query) > 0) {

					$subscription_meta_ = mysqli_fetch_assoc($subscription_meta_query);
					$subscription_meta_result = json_decode($subscription_meta_['meta_value'], true);

					$user_meta_query = mysqli_query($conn, "SELECT subscription_id FROM users WHERE id='$admin_id' && active_status='1' && delete_status='0'");

					if(mysqli_num_rows($user_meta_query) > 0) {
						$user_meta_result = mysqli_fetch_assoc($user_meta_query);
						$subscription_id = $user_meta_result['subscription_id'];

						$user_subscription_transaction = json_decode(user_meta($admin_id, 'user_subscription'), true);

						if(time() < $user_subscription_transaction['next_payment_billing']) {

							if($subscription_meta_result['urgent_email']['limit'] == '-1') {
								if($user_role['inbox']['urgent_email'] == 1) {
									echo json_encode(array('status' => 'success', 'status_code' => '001'));
								} else {
									echo json_encode(array('status' => 'error', 'status_code' => '002'));
								}
							} else {
								if(mysqli_num_rows(mysqli_query($conn, "SELECT * FROM inbox WHERE user_id='$user_id' && mailbox='sent' && is_important='1' && type='0' && date_created >= CURDATE()")) < ($subscription_meta_result['urgent_email']['limit'] === false ? -1 : $subscription_meta_result['urgent_email']['limit'])) {
									if($user_role['inbox']['urgent_email'] == 1) {
										echo json_encode(array('status' => 'success', 'status_code' => '001'));
									} else {
										echo json_encode(array('status' => 'success', 'status_code' => '002'));
									}
								} else {
									echo json_encode(array('status' => 'error', 'status_code' => '002', 'status_subcode' => '2001'));
								}
							}
							
						} else {
							echo json_encode(array('status' => 'error', 'status_code' => '002', 'status_subcode' => '2002'));
						}
					} else {
						echo json_encode(array('status' => 'error', 'status_code' => '002'));
					}
		
				} else {
					echo json_encode(array('status' => 'error', 'status_code' => '002'));
				}
			} else {
				echo json_encode(array('status' => 'error', 'status_code' => '002'));
			}
		} else {
			echo json_encode(array('status' => 'error', 'status_code' => '002'));
		}

	} else {
		echo json_encode(array('status' => 'error', 'status_code' => '003'));
	}

}

if(isset($data['action']) && $data['action'] == 'get_track_email_info') {
	
	$user_id = trim(strip_tags(mysqli_real_escape_string($conn, $data['user_id'])));
	$account_id = trim(strip_tags(mysqli_real_escape_string($conn, $data['account_id'])));
	$admin_id = trim(strip_tags(mysqli_real_escape_string($conn, admin_id($user_id))));
	$user_role = json_decode(user_meta($user_id, 'user_role'), true);

	if($user_id != '' && $user_id != 0 && $account_id != '' && $account_id != 0) {

		$user_meta_query = mysqli_query($conn, "SELECT subscription_id FROM users WHERE id='$admin_id' && active_status='1' && delete_status='0'");

		if(mysqli_num_rows($user_meta_query) > 0) {
			$user_meta_result = mysqli_fetch_assoc($user_meta_query);
			$subscription_id = $user_meta_result['subscription_id'];

			$subscription_query = mysqli_query($conn, "SELECT * FROM subscriptions WHERE id='$subscription_id' && active_status='1' && delete_status='0'");
			if(mysqli_num_rows($subscription_query) > 0) {
				$subscription_meta_query = mysqli_query($conn, "SELECT meta_value FROM subscription_meta WHERE subscription_id='$subscription_id' && meta_key='features'");
				if(mysqli_num_rows($subscription_meta_query) > 0) {

					$subscription_meta_ = mysqli_fetch_assoc($subscription_meta_query);
					$subscription_meta_result = json_decode($subscription_meta_['meta_value'], true);

					$user_meta_query = mysqli_query($conn, "SELECT subscription_id FROM users WHERE id='$admin_id' && active_status='1' && delete_status='0'");

					if(mysqli_num_rows($user_meta_query) > 0) {
						$user_meta_result = mysqli_fetch_assoc($user_meta_query);
						$subscription_id = $user_meta_result['subscription_id'];

						$user_subscription_transaction = json_decode(user_meta($admin_id, 'user_subscription'), true);

						if(time() < $user_subscription_transaction['next_payment_billing']) {

							if($subscription_meta_result['tracking_email']['limit'] == '-1') {
								if($user_role['inbox']['tracking_email'] == 1) {
									echo json_encode(array('status' => 'success', 'status_code' => '001'));
								} else {
									echo json_encode(array('status' => 'error', 'status_code' => '002'));
								}
							} else {
								if(mysqli_num_rows(mysqli_query($conn, "SELECT * FROM inbox WHERE user_id='$user_id' && mailbox='sent' && is_tracked='1' && type='0' && date_created = CURDATE()")) < ($subscription_meta_result['tracking_email']['limit'] == false ? -1 : $subscription_meta_result['tracking_email']['limit'])) {
									if($user_role['inbox']['tracking_email'] == 1) {
										echo json_encode(array('status' => 'success', 'status_code' => '001'));
									} else {
										echo json_encode(array('status' => 'error', 'status_code' => '002'));
									}
								} else {
									echo json_encode(array('status' => 'error', 'status_code' => '002', 'status_subcode' => '2001'));
								}
							}
							
						} else {
							echo json_encode(array('status' => 'error', 'status_code' => '002', 'status_subcode' => '2002'));
						}
					} else {
						echo json_encode(array('status' => 'error', 'status_code' => '002'));
					}
		
				} else {
					echo json_encode(array('status' => 'error', 'status_code' => '002'));
				}
			} else {
				echo json_encode(array('status' => 'error', 'status_code' => '002'));
			}
		} else {
			echo json_encode(array('status' => 'error', 'status_code' => '002'));
		}

	} else {
		echo json_encode(array('status' => 'error', 'status_code' => '003'));
	}

}

if(isset($data['action']) && $data['action'] == 'compose') {

	$user_id = trim(strip_tags(mysqli_real_escape_string($conn, $data['user_id'])));
	$account_id = trim(strip_tags(mysqli_real_escape_string($conn, $data['from_address'])));
	$to_address = trim(strip_tags(mysqli_real_escape_string($conn, $data['to_address'])));
	$cc_address = trim(strip_tags(mysqli_real_escape_string($conn, $data['cc_address'])));
	$bcc_address = trim(strip_tags(mysqli_real_escape_string($conn, $data['bcc_address'])));
	$subject = trim(strip_tags(mysqli_real_escape_string($conn, $data['subject'])));
	$message = $data['message'];
	$attachments = trim(strip_tags(mysqli_real_escape_string($conn, $data['attachments'])));
	$is_important = trim(strip_tags(mysqli_real_escape_string($conn, $data['is_important'])));
	$is_tracked = trim(strip_tags(mysqli_real_escape_string($conn, $data['is_tracked'])));
	$is_schedule = trim(strip_tags(mysqli_real_escape_string($conn, $data['is_schedule'])));
	if($is_schedule == 1) {
		$is_completed = 0;
	} else {
		$is_completed = 1;
	}
	$schedule_time = trim(strtotime(strip_tags(mysqli_real_escape_string($conn, $data['schedule_time']))));
	$date = date('r');

	if($user_id != '' && $account_id != '' && $to_address != '' && $subject != '') {

		$from_address_query = mysqli_query($conn, "SELECT * FROM accounts WHERE (user_id='$user_id' || admin_id='$user_id') && id='$account_id' && active_status='1' && delete_status='0' && verified_status='1'");
		if(mysqli_num_rows($from_address_query) > 0) {
			$from_address_result = mysqli_fetch_assoc($from_address_query);
			$from_email = $from_address_result['account_email'];
			$from_title = $from_address_result['account_title'];
			$account_host = explode('@', $from_address_result['account_email']);
			$SMTP = new SMTP($account_host[1], $from_address_result['account_email'], $from_address_result['account_password']);

			$subject = str_replace('â€', '"', str_replace('â€“', '-', str_replace('â€œ', '"', str_replace('â€™', "'", str_replace('Â', "", $subject)))));
			
			if($is_schedule == 0) {
				if($cc_address != '') {
					$SMTP->addCCAddress(trim($cc_address));
				}

				if($bcc_address != '') {
					$SMTP->addBCCAddress(trim($bcc_address));
				}
				$SMTP->addSubject(trim($subject));

				if($attachments != '') {
					$SMTP->addAttachments(trim($attachments));
				}

				$to_address_array = explode(',', $to_address);

				$i = 1;

				foreach($to_address_array as $to) {
					$SMTP->addToAddress(trim($to));
					$i++;
				}
			}

			if($is_tracked == 1) {

				$tracking_type = 0;
				$redirect_token = md5(rand(0, 100000).time().microtime(true));
				$track_link = $dashboard_url.'tracking.php?type='.$tracking_type.'&redirect_token='.$redirect_token;

				$message = "<img src='".$track_link."' width='1px' height='1px'>".$message;

				if($is_schedule == 0) {
					$SMTP->addMessage(trim($message));
				}

			} else {
				if($is_schedule == 0) {
					$SMTP->addMessage(trim($message));
				}
			}

			$content = htmlentities(htmlspecialchars($message));
			$query = mysqli_query($conn, "INSERT INTO inbox(account_id, user_id, mailbox, from_address, to_address, is_important, is_completed, subject, content, udate, time_created) VALUES('$account_id', '$user_id', 'sent', '$from_email', '$to_address', '$is_important', '$is_completed', '$subject', '$content', '$time_created', '$time_created')");
			$inbox_id = mysqli_insert_id($conn);
			$inbox_meta_query = mysqli_query($conn, "INSERT INTO inbox_meta(inbox_id, meta_key, meta_value) VALUES('$inbox_id', 'message_id', ''), ('$inbox_id', 'from_address', '$from_title'), ('$inbox_id', 'cc_address', '$cc_address'), ('$inbox_id', 'date', '$date'), ('$inbox_id', 'size', ''), ('$inbox_id', 'is_schedule', '$is_schedule'), ('$inbox_id', 'schedule_time', '$schedule_time')");
			if($is_tracked == 1) {
				$tracking_query = mysqli_query($conn, "INSERT INTO tracking_links(user_id, inbox_id, href_links, redirect_token, type, time_created) VALUES('$user_id', '$inbox_id', '$track_link', '$redirect_token', '0', '$time_created')");
			}

			if($attachments != '') {
				$attachments_array = explode(',', $attachments);
				foreach($attachments_array as $attachment) {
					$attachment_info = pathinfo($dashboard_url.$attachment);
					$attachment_name = trim($attachment_info['basename']);
					$attachment_type = trim($attachment_info['extension']);
					$attachment = trim($attachment);
					$attachment_query = mysqli_query($conn, "INSERT INTO inbox_attachments(inbox_id, attachment_name, attachment_url, attachment_type, time_created) VALUES('$inbox_id', '$attachment_name', '$attachment', '$attachment_type', '$time_created')");
				}
			}

			if($query && $inbox_meta_query) {
				if($is_schedule == 0) {
					if($SMTP->sendNormalEmail($is_important)) {
						echo json_encode(array('status' => 'success', 'status_code' => '001'));
					} else {
						$delete_inbox_query = mysqli_query($conn, "DELETE FROM inbox WHERE id='$inbox_id'");
						$delete_inbox_meta_query = mysqli_query($conn, "DELETE FROM inbox_meta WHERE inbox_id='$inbox_id'");
						$delete_attachment_query = mysqli_query($conn, "DELETE FROM inbox_attachments WHERE inbox_id='$inbox_id'");
						echo json_encode(array('status' => 'error', 'status' => '002'));
					}
				} else {
					echo json_encode(array('status' => 'success', 'status_code' => '001'));
				}
			} else {
				echo json_encode(array('status' => 'error', 'status' => '002'));
			}

		} else {
			echo json_encode(array('status' => 'error', 'status' => '002'));
		}

	} else {
		echo json_encode(array('status' => 'success', 'status_code' => '003'));
	}

}

if(isset($data['action']) && $data['action'] == 'save_draft') {

	$user_id = trim(strip_tags(mysqli_real_escape_string($conn, $data['user_id'])));
	$message_id = trim(strip_tags(mysqli_real_escape_string($conn, $data['message_id'])));
	$account_id = trim(strip_tags(mysqli_real_escape_string($conn, $data['from_address'])));
	$to_address = trim(strip_tags(mysqli_real_escape_string($conn, $data['to_address'])));
	$cc_address = trim(strip_tags(mysqli_real_escape_string($conn, $data['cc_address'])));
	$bcc_address = trim(strip_tags(mysqli_real_escape_string($conn, $data['bcc_address'])));
	$subject = trim(strip_tags(mysqli_real_escape_string($conn, $data['subject'])));
	$message = $data['message'];
	$attachments = trim(strip_tags(mysqli_real_escape_string($conn, $data['attachments'])));
	$is_important = trim(strip_tags(mysqli_real_escape_string($conn, $data['is_important'])));
	$is_tracked = trim(strip_tags(mysqli_real_escape_string($conn, $data['is_tracked'])));
	$is_schedule = trim(strip_tags(mysqli_real_escape_string($conn, $data['is_schedule'])));
	$schedule_time = trim(strip_tags(mysqli_real_escape_string($conn, $data['schedule_time'])));
	$date = date('r');

	if($user_id != '' && $user_id != 0 && $account_id != '' && $account_id != 0 && $to_address != '') {

		if($message_id != '') {
			$from_address_query = mysqli_query($conn, "SELECT * FROM accounts WHERE (user_id='$user_id' || admin_id='$user_id') && id='$account_id' && active_status='1' && delete_status='0' && verified_status='1'");
			if(mysqli_num_rows($from_address_query) > 0) {
				$from_address_result = mysqli_fetch_assoc($from_address_query);
				$from_email = $from_address_result['account_email'];
				$from_title = $from_address_result['account_title'];

				$content = htmlentities(htmlspecialchars($message));
				$query = mysqli_query($conn, "UPDATE inbox SET account_id='$account_id', from_address='$from_email', to_address='$to_address', is_important='$is_important', is_tracked='$is_tracked', subject='$subject', content='$content' WHERE id='$message_id'");
				$inbox_meta_query = mysqli_query($conn, "UPDATE inbox_meta SET meta_value= CASE
					WHEN meta_key='from_address' THEN '$from_title'
					WHEN meta_key='cc_address' THEN '$cc_address'
					WHEN meta_key='is_schedule' THEN '$is_schedule'
					WHEN meta_key='schedule_time' THEN '$schedule_time'
					ELSE `meta_value`
					END
					WHERE inbox_id='$message_id'");
				
				if($attachments != '') {
					$attachments_array = explode(',', $attachments);
					foreach($attachments_array as $attachment) {
						$attachment_info = pathinfo($dashboard_url.$attachment);
						$attachment_name = trim($attachment_info['basename']);
						$attachment_type = trim($attachment_info['extension']);
						$attachment = trim($attachment);
						$attachment_query = mysqli_query($conn, "INSERT INTO inbox_attachments(inbox_id, attachment_name, attachment_url, attachment_type, time_created) VALUES('$message_id', '$attachment_name', '$attachment', '$attachment_type', '$time_created')");
					}
				}

				if($query && $inbox_meta_query) {
					echo json_encode(array('status' => 'success', 'status_code' => '001'));
				} else {
					echo json_encode(array('status' => 'error', 'status' => '002'));
				}
			}
		} else {

			$admin_id_query = mysqli_query($conn, "SELECT admin_id FROM users WHERE id='$user_id' && active_status='1' && delete_status='0'");
			if(mysqli_num_rows($admin_id_query) > 0) {
				$admin_id_result = mysqli_fetch_assoc($admin_id_query);
				if(is_null($admin_id_result['admin_id'])) {
					$admin_id = $user_id;
				} else {
					$admin_id = $admin_id_result['admin_id'];
				}
			} else {
				$admin_id = $user_id;
			}

			$from_address_query = mysqli_query($conn, "SELECT * FROM accounts WHERE (user_id='$user_id' || admin_id='$user_id') && id='$account_id' && active_status='1' && delete_status='0' && verified_status='1'");
			if(mysqli_num_rows($from_address_query) > 0) {
				$from_address_result = mysqli_fetch_assoc($from_address_query);
				$from_email = $from_address_result['account_email'];
				$from_title = $from_address_result['account_title'];

				$content = htmlentities(htmlspecialchars($message));
				$query = mysqli_query($conn, "INSERT INTO inbox(user_id, admin_id, account_id, mailbox, from_address, to_address, is_important, subject, content, type, udate, time_created) VALUES('$user_id', '$admin_id', '$account_id', 'drafts', '$from_email', '$to_address', '$is_important', '$subject', '$content', '0', '$time_created', '$time_created')");
				$inbox_id = mysqli_insert_id($conn);
				$inbox_meta_query = mysqli_query($conn, "INSERT INTO inbox_meta(inbox_id, meta_key, meta_value) VALUES('$inbox_id', 'message_id', ''), ('$inbox_id', 'from_address', '$from_title'), ('$inbox_id', 'cc_address', '$cc_address'), ('$inbox_id', 'date', '$date'), ('$inbox_id', 'size', '')");

				if($attachments != '') {
					$attachments_array = explode(',', $attachments);
					foreach($attachments_array as $attachment) {
						$attachment_info = pathinfo($dashboard_url.$attachment);
						$attachment_name = trim($attachment_info['basename']);
						$attachment_type = trim($attachment_info['extension']);
						$attachment = trim($attachment);
						$attachment_query = mysqli_query($conn, "INSERT INTO inbox_attachments(inbox_id, attachment_name, attachment_url, attachment_type, time_created) VALUES('$inbox_id', '$attachment_name', '$attachment', '$attachment_type', '$time_created')");
					}
				}

				if($query && $inbox_meta_query) {
					echo json_encode(array('status' => 'success', 'status_code' => '001'));
				} else {
					echo json_encode(array('status' => 'error', 'status' => '002'));
				}
			}

		}

	} else {
		echo json_encode(array('status' => 'success', 'status_code' => '003'));
	}
}

if(isset($data['action']) && $data['action'] == 'get_sent') {

	$mailbox = trim(strip_tags(mysqli_real_escape_string($conn, $data['mailbox'])));
	$account_id = trim(strip_tags(mysqli_real_escape_string($conn, $data['account_id'])));
	$user_id = trim(strip_tags(mysqli_real_escape_string($conn, $data['user_id'])));
	$page = trim(strip_tags(mysqli_real_escape_string($conn, $data['page'])));
	$per_page_record = trim(strip_tags(mysqli_real_escape_string($conn, $data['per_page_record'])));

	if($user_id != '') {
		$total_record = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM inbox WHERE account_id='$account_id' && mailbox='$mailbox' && parent_id is NULL && delete_status='0'"));
		$total_pages = ceil($total_record / $per_page_record);
		$offset = ($page - 1) * $per_page_record;
		$query = mysqli_query($conn, "SELECT id, account_id, uid, mailbox, from_address, to_address, is_starred, is_important, seen_status, subject, content, udate, time_created FROM inbox WHERE account_id='$account_id' && mailbox='$mailbox' && parent_id is NULL && delete_status='0' ORDER BY time_created DESC LIMIT $offset, $per_page_record");

		$result = array();

		if(mysqli_num_rows($query) > 0) {
			while($row = mysqli_fetch_assoc($query)) {
				$inbox_id = $row['id'];
				$result['id_'.$row['id']] = array(
					'id' => $row['id'], 
					'account_id' => $row['account_id'], 
					'uid' => $row['uid'],
					'mailbox' => $row['mailbox'],
					'from_address' => $row['from_address'],
					'to_address' => $row['to_address'],
					'is_starred' => $row['is_starred'],
					'is_important' => $row['is_important'],
					'seen_status' => $row['seen_status'],
					'subject' => $row['subject'],
					'content' => html_entity_decode(htmlspecialchars_decode($row['content'])),
					'udate' => time_ago($row['time_created']),
					'time_created' => $row['time_created']
				);
				$meta_query = mysqli_query($conn, "SELECT * FROM inbox_meta WHERE inbox_id='$inbox_id'");
				if(mysqli_num_rows($meta_query) > 0) {
					while($meta_result = mysqli_fetch_assoc($meta_query)) {
						$result['id_'.$row['id']] = array_merge(array($meta_result['meta_key'] => $meta_result['meta_value']), $result['id_'.$row['id']]);
					}
				}
				
			}
			$records = array('display_records' => $result);
			echo json_encode(array_merge(array('status' => 'success', 'status_code' => '001', 'total_record' => $total_record, 'total_pages' => $total_pages, 'offset' => $offset), $records));
		} else {
			echo json_encode(array('status' => 'error', 'status_code' => '004', 'total_record' => $total_record));
		}

	} else {
		echo json_encode(array('status' => 'error', 'status_code' => '002'));
	}

}

if(isset($data['action']) && $data['action'] == 'get_schedule') {

	$mailbox = trim(strip_tags(mysqli_real_escape_string($conn, $data['mailbox'])));
	$account_id = trim(strip_tags(mysqli_real_escape_string($conn, $data['account_id'])));
	$user_id = trim(strip_tags(mysqli_real_escape_string($conn, $data['user_id'])));
	$page = trim(strip_tags(mysqli_real_escape_string($conn, $data['page'])));
	$per_page_record = trim(strip_tags(mysqli_real_escape_string($conn, $data['per_page_record'])));

	if($user_id != '') {
		$total_record = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM inbox WHERE account_id='$account_id' && mailbox='$mailbox' && is_completed='0' && parent_id is NULL && delete_status='0'"));
		$total_pages = ceil($total_record / $per_page_record);
		$offset = ($page - 1) * $per_page_record;
		$query = mysqli_query($conn, "SELECT id, account_id, uid, mailbox, from_address, to_address, is_starred, is_important, seen_status, subject, content, udate, time_created FROM inbox WHERE account_id='$account_id' && mailbox='$mailbox' && is_completed='0' && parent_id is NULL && delete_status='0' ORDER BY time_created DESC LIMIT $offset, $per_page_record");

		$result = array();

		if(mysqli_num_rows($query) > 0) {
			while($row = mysqli_fetch_assoc($query)) {
				$inbox_id = $row['id'];
				$result['id_'.$row['id']] = array(
					'id' => $row['id'], 
					'account_id' => $row['account_id'], 
					'uid' => $row['uid'],
					'mailbox' => $row['mailbox'],
					'from_address' => $row['from_address'],
					'to_address' => $row['to_address'],
					'is_starred' => $row['is_starred'],
					'is_important' => $row['is_important'],
					'seen_status' => $row['seen_status'],
					'subject' => $row['subject'],
					'content' => html_entity_decode(htmlspecialchars_decode($row['content'])),
					'schedule_time' => date('d-M-Y H:i:s A', inbox_meta($inbox_id, 'schedule_time')),
					'time_created' => $row['time_created']
				);
				
			}
			$records = array('display_records' => $result);
			echo json_encode(array_merge(array('status' => 'success', 'status_code' => '001', 'total_record' => $total_record, 'total_pages' => $total_pages, 'offset' => $offset), $records));
		} else {
			echo json_encode(array('status' => 'error', 'status_code' => '004', 'total_record' => $total_record));
		}

	} else {
		echo json_encode(array('status' => 'error', 'status_code' => '002'));
	}

}

if(isset($data['action']) && $data['action'] == 'get_templates') {

	$user_id = trim(strip_tags(mysqli_real_escape_string($conn, $data['user_id'])));

	if($user_id != '') {

		$query = mysqli_query($conn, "SELECT id, user_id, template_name, category_id, active_status, time_created FROM templates WHERE delete_status='0' ORDER BY time_created DESC");

		$result = array();

		if(mysqli_num_rows($query) > 0) {
			while($row = mysqli_fetch_assoc($query)) {
				$inbox_id = $row['id'];
				$result['id_'.$row['id']] = array(
					'id' => $row['id'], 
					'user_id' => $row['user_id'], 
					'template_name' => $row['template_name'],
					'category_name' => template_category($row['category_id']),
					'active_status' => $row['active_status'],
					'time_created' => $row['time_created']
				);
				
			}
			$records = array('display_records' => $result);
			echo json_encode(array_merge(array('status' => 'success', 'status_code' => '001', 'total_record' => mysqli_num_rows($query)), $records));
		} else {
			echo json_encode(array('status' => 'error', 'status_code' => '004', 'total_record' => mysqli_num_rows($query)));
		}

	} else {
		echo json_encode(array('status' => 'error', 'status_code' => '002'));
	}

}

if(isset($data['action']) && $data['action'] == 'single_template') {

	$template_id = trim(strip_tags(mysqli_real_escape_string($conn, $data['template_id'])));
	$user_id = trim(strip_tags(mysqli_real_escape_string($conn, $data['user_id'])));

	if($template_id != '' && $template_id != 0 && $user_id != '' && $user_id != 0) {
		
		$query = mysqli_query($conn, "SELECT id, user_id, template_name, template_content, category_id, active_status, time_created FROM templates WHERE id='$template_id' && delete_status='0'");

		$result = array();

		if(mysqli_num_rows($query) > 0) {
			$row = mysqli_fetch_assoc($query);

			$result = array(
				'id' => $row['id'], 
				'user_id' => $row['user_id'], 
				'template_name' => $row['template_name'],
				'content' => html_entity_decode(htmlspecialchars_decode($row['template_content'])),
				'time_created' => $row['time_created']
			);

			$records = array('display_records' => $result);
			echo json_encode(array_merge(array('status' => 'success', 'status_code' => '001', 'total_record' => mysqli_num_rows($query)), $records));
		} else {
			echo json_encode(array('status' => 'error', 'status_code' => '004', 'total_record' => mysqli_num_rows($query)));
		}

	} else {
		echo json_encode(array('status' => 'error', 'status_code' => '002'));
	}

}

if(isset($data['action']) && $data['action'] == 'duplicate_template') {

	$template_id = trim(strip_tags(mysqli_real_escape_string($conn, $data['template_id'])));
	$user_id = trim(strip_tags(mysqli_real_escape_string($conn, $data['user_id'])));

	if($template_id != '' && $user_id != '') {

		$user_meta_query = mysqli_query($conn, "SELECT subscription_id FROM users WHERE id='$user_id' && active_status='1' && delete_status='0'");

		if(mysqli_num_rows($user_meta_query) > 0) {
			$user_meta_result = mysqli_fetch_assoc($user_meta_query);
			$subscription_id = $user_meta_result['subscription_id'];

			$subscription_query = mysqli_query($conn, "SELECT * FROM subscriptions WHERE id='$subscription_id' && active_status='1' && delete_status='0'");
			if(mysqli_num_rows($subscription_query) > 0) {
				$subscription_meta_query = mysqli_query($conn, "SELECT meta_value FROM subscription_meta WHERE subscription_id='$subscription_id' && meta_key='features'");
				if(mysqli_num_rows($subscription_meta_query) > 0) {

					$subscription_meta_ = mysqli_fetch_assoc($subscription_meta_query);
					$subscription_meta_result = json_decode($subscription_meta_['meta_value'], true);

					if(mysqli_num_rows(mysqli_query($conn, "SELECT * FROM templates WHERE user_id='$user_id'")) < $subscription_meta_result['email_templates']['limit']) {

						$get_template_query = mysqli_query($conn, "SELECT * FROM templates WHERE id='$template_id' && active_status='1' && delete_status='0'");
						if(mysqli_num_rows($get_template_query) > 0) {
							$get_template_result = mysqli_fetch_assoc($get_template_query);
							$template_name = $get_template_result['template_name'];
							$template_content = $get_template_result['template_content'];
							$template_content_type = $get_template_result['content_type'];
							$category_id = $get_template_result['category_id'];

							if(is_null($category_id)) {
								$query = mysqli_query($conn, "INSERT INTO templates(user_id, template_name, template_content, content_type, time_created) VALUES('$user_id', '$template_name', '$template_content', '$template_content_type', '$time_created')");
							} else {
								$query = mysqli_query($conn, "INSERT INTO templates(user_id, template_name, template_content, content_type, category_id, time_created) VALUES('$user_id', '$template_name', '$template_content', '$template_content_type', '$category_id', '$time_created')");
							}

							if($query) {
								echo json_encode(array('status' => 'success', 'status_code' => '001'));
							} else {
								echo json_encode(array('status' => 'error', 'status_code' => '002'));
							}

						} else {
							echo json_encode(array('status' => 'error', 'status_code' => '002'));
						}

					} else {
						echo json_encode(array('status' => 'error', 'status_code' => '002', 'status_subcode' => '2001'));
					}

				} else {
					echo json_encode(array('status' => 'error', 'status_code' => '002'));
				}
			} else {
				echo json_encode(array('status' => 'error', 'status_code' => '002'));
			}
		} else {
			echo json_encode(array('status' => 'error', 'status_code' => '002'));
		}
		
	}

}

if(isset($data['action']) && $data['action'] == 'delete_template') {

	$template_id = trim(strip_tags(mysqli_real_escape_string($conn, $data['template_id'])));
	$user_id = trim(strip_tags(mysqli_real_escape_string($conn, $data['user_id'])));

	if($template_id != '' && $template_id != 0 && $user_id != '' && $user_id != 0) {

		$query = mysqli_query($conn, "DELETE FROM templates WHERE id='$template_id' && user_id='$user_id'");
		if($query) {
			echo json_encode(array('status' => 'success', 'status_code' => '001'));
		} else {
			echo json_encode(array('status' => 'error', 'status_code' => '002'));
		}

	}

}

if(isset($data['action']) && $data['action'] == 'new_template') {

	$template_name = trim(strip_tags(mysqli_real_escape_string($conn, $data['template_name'])));
	$template_category = trim(strip_tags(mysqli_real_escape_string($conn, $data['template_category'])));
	$user_id = trim(strip_tags(mysqli_real_escape_string($conn, $data['user_id'])));

	if($template_name != '' && $user_id != '') {

		$user_meta_query = mysqli_query($conn, "SELECT subscription_id FROM users WHERE id='$user_id' && active_status='1' && delete_status='0'");

		if(mysqli_num_rows($user_meta_query) > 0) {
			$user_meta_result = mysqli_fetch_assoc($user_meta_query);
			$subscription_id = $user_meta_result['subscription_id'];

			$subscription_query = mysqli_query($conn, "SELECT * FROM subscriptions WHERE id='$subscription_id' && active_status='1' && delete_status='0'");
			if(mysqli_num_rows($subscription_query) > 0) {
				$subscription_meta_query = mysqli_query($conn, "SELECT meta_value FROM subscription_meta WHERE subscription_id='$subscription_id' && meta_key='features'");
				if(mysqli_num_rows($subscription_meta_query) > 0) {

					$subscription_meta_ = mysqli_fetch_assoc($subscription_meta_query);
					$subscription_meta_result = json_decode($subscription_meta_['meta_value'], true);

					if(mysqli_num_rows(mysqli_query($conn, "SELECT * FROM templates WHERE user_id='$user_id'")) < $subscription_meta_result['email_templates']['limit']) {

						if($template_category == '') {
							$query = mysqli_query($conn, "INSERT INTO templates(user_id, template_name, time_created) VALUES('$user_id', '$template_name', '$time_created')");
						} else {
							$query = mysqli_query($conn, "INSERT INTO templates(user_id, template_name, category_id, time_created) VALUES('$user_id', '$template_name', '$template_category', '$time_created')");
						}

						if($query) {
							echo json_encode(array('status' => 'success', 'status_code' => '001'));
						} else {
							echo json_encode(array('status' => 'error', 'status_code' => '002'));
						}

					} else {
						echo json_encode(array('status' => 'error', 'status_code' => '002', 'status_subcode' => '2001'));
					}

				} else {
					echo json_encode(array('status' => 'error', 'status_code' => '002'));
				}
			} else {
				echo json_encode(array('status' => 'error', 'status_code' => '002'));
			}
		} else {
			echo json_encode(array('status' => 'error', 'status_code' => '002'));
		}

	} else {
		echo json_encode(array('status' => 'error', 'status_code' => '003'));
	}

}

if(isset($data['action']) && $data['action'] == 'get_template_categories') {

	$query = mysqli_query($conn, "SELECT id, category_name, active_status FROM template_categories WHERE delete_status='0'");

	$result = array();

	if(mysqli_num_rows($query) > 0) {
		while($row = mysqli_fetch_assoc($query)) {
			$result['id_'.$row['id']] = $row;
		}
		$records = array('display_records' => $result);
		echo json_encode(array_merge(array('status' => 'success', 'status_code' => '001', 'total_record' => mysqli_num_rows($query)), $records));
	} else {
		echo json_encode(array('status' => 'error', 'status_code' => '004', 'total_record' => mysqli_num_rows($query)));
	}

}

if(isset($data['action']) && $data['action'] == 'save_template') {

	$template_id = trim(strip_tags(mysqli_real_escape_string($conn, $data['template_id'])));
	$user_id = trim(strip_tags(mysqli_real_escape_string($conn, $data['user_id'])));
	$content = htmlentities(htmlspecialchars($data['content']));

	if($template_id != '' && $user_id != '') {
		$check_template_query = mysqli_query($conn, "SELECT * FROM templates WHERE user_id='$user_id' && id='$template_id' && active_status='1'");
		if(mysqli_num_rows(mysqli_query($conn, "SELECT * FROM templates WHERE user_id='$user_id' && id='$template_id' && active_status='1'")) == 1) {
			$query = mysqli_query($conn, "UPDATE templates SET template_content='$content' WHERE id='$template_id' && user_id='$user_id'");
			if($query) {
				echo json_encode(array('status' => 'success', 'status_code' => '001'));
			} else {
				echo json_encode(array('status' => 'success', 'status_code' => '002'));
			}
		} else {
			$query = mysqli_query($conn, "INSERT INTO templates(user_id, template_content, time_created) VALUES('$user_id', '$content', '$time_created')");
			if($query) {
				echo json_encode(array('status' => 'success', 'status_code' => '001'));
			} else {
				echo json_encode(array('status' => 'error', 'status_code' => '002'));
			}
		}
	} else {
		echo json_encode(array('status' => 'success', 'status_code' => '003'));
	}

}

if(isset($data['action']) && $data['action'] == 'get_contact_lists') {

	$user_id = trim(strip_tags(mysqli_real_escape_string($conn, $data['user_id'])));

	if($user_id != '' && $user_id != 0) {

		$query = mysqli_query($conn, "SELECT id, user_id, admin_id, contact_list_title, active_status, time_created FROM contact_lists WHERE (user_id='$user_id' || admin_id='$user_id') && delete_status='0' ORDER BY time_created DESC");

		$result = array();

		if(mysqli_num_rows($query) > 0) {
			$i = 0;
			while($row = mysqli_fetch_assoc($query)) {

				$contact_id = $row['id'];
				$result[$i] = array(
					'id' => $row['id'], 
					'user_id' => $row['user_id'], 
					'admin_id' => $row['admin_id'], 
					'contact_list_title' => $row['contact_list_title'],
					'total_contacts' => mysqli_num_rows(mysqli_query($conn, "SELECT * FROM contact_email_relationship WHERE contact_list_id='$contact_id' && active_status='1' && delete_status='0'")), 
					'active_status' => $row['active_status'],
					'time_created' => $row['time_created']
				);
				$i++;
				
			}
			$records = array('display_records' => $result);
			echo json_encode(array_merge(array('status' => 'success', 'status_code' => '001', 'total_record' => mysqli_num_rows($query)), $records));
		} else {
			echo json_encode(array('status' => 'error', 'status_code' => '004', 'total_record' => mysqli_num_rows($query)));
		}

	}

}

if(isset($data['action']) && $data['action'] == 'single_contact_list') {

	$contact_list_id = trim(strip_tags(mysqli_real_escape_string($conn, $data['contact_list_id'])));
	$user_id = trim(strip_tags(mysqli_real_escape_string($conn, $data['user_id'])));

	if($contact_list_id != '' && $user_id != '') {
		$query = mysqli_query($conn, "SELECT id, user_id, contact_list_title, contact_list_description, active_status, time_created FROM contact_lists WHERE id='$contact_list_id' && user_id='$user_id' && delete_status='0' ORDER BY time_created DESC");

		$result = array();

		if(mysqli_num_rows($query) > 0) {
			$result = mysqli_fetch_assoc($query);
			$records = array('display_records' => $result);
			echo json_encode(array_merge(array('status' => 'success', 'status_code' => '001', 'total_record' => mysqli_num_rows($query)), $records));
		} else {
			echo json_encode(array('status' => 'error', 'status_code' => '004', 'total_record' => mysqli_num_rows($query)));
		}

	} else {
		echo json_encode(array('status' => 'error', 'status_code' => '002'));
	}

}

if(isset($data['action']) && $data['action'] == 'new_contact_list') {

	$user_id = trim(strip_tags(mysqli_real_escape_string($conn, $data['user_id'])));
	$contact_list_title = trim(strip_tags(mysqli_real_escape_string($conn, $data['contact_list_title'])));
	$contact_list_description = trim(strip_tags(mysqli_real_escape_string($conn, $data['contact_list_description'])));
	$email_accounts_list = trim(strip_tags(mysqli_real_escape_string($conn, $data['email_accounts_list'])));

	if($contact_list_title != '' && $user_id != '' && $user_id != 0) {

		$admin_id_query = mysqli_query($conn, "SELECT admin_id FROM users WHERE id='$user_id' && active_status='1' && delete_status='0'");
		if(mysqli_num_rows($admin_id_query) > 0) {
			$admin_id_result = mysqli_fetch_assoc($admin_id_query);
			if(is_null($admin_id_result['admin_id'])) {
				$admin_id = $user_id;
			} else {
				$admin_id = $admin_id_result['admin_id'];
			}
		} else {
			$admin_id = $user_id;
		}

		/*
		$user_meta_query = mysqli_query($conn, "SELECT subscription_id FROM users WHERE id='$user_id' && active_status='1' && delete_status='0'");

		if(mysqli_num_rows($user_meta_query) > 0) {
			$user_meta_result = mysqli_fetch_assoc($user_meta_query);
			$subscription_id = $user_meta_result['subscription_id'];

			$subscription_query = mysqli_query($conn, "SELECT * FROM subscriptions WHERE id='$subscription_id' && active_status='1' && delete_status='0'");
			if(mysqli_num_rows($subscription_query) > 0) {
				$subscription_meta_query = mysqli_query($conn, "SELECT meta_value FROM subscription_meta WHERE subscription_id='$subscription_id' && meta_key='features'");
				if(mysqli_num_rows($subscription_meta_query) > 0) {

					$subscription_meta_ = mysqli_fetch_assoc($subscription_meta_query);
					$subscription_meta_result = json_decode($subscription_meta_['meta_value'], true);

					if(mysqli_num_rows(mysqli_query($conn, "SELECT * FROM templates WHERE user_id='$templates'")) < $subscription_meta_result['email_templates']['limit']) {
					*/

						$query = mysqli_query($conn, "INSERT INTO contact_lists(user_id, admin_id, contact_list_title, contact_list_description, time_created) VALUES('$user_id', '$admin_id', '$contact_list_title', '$contact_list_description', '$time_created')");
						$contact_list_id = mysqli_insert_id($conn);

						if($email_accounts_list != '') {
							$email_accounts_array = explode(',', $email_accounts_list);
							foreach($email_accounts_array as $email_accounts) {
								$email_accounts = trim(strip_tags(mysqli_real_escape_string($conn, $email_accounts)));
								$email_accounts_query = mysqli_query($conn, "INSERT INTO contact_email_accounts(user_id, admin_id, email_address, time_created) VALUES('$user_id', '$admin_id', '$email_accounts', '$time_created')");
								$email_account_id = mysqli_insert_id($conn);
								$account_contact_list_query = mysqli_query($conn, "INSERT INTO contact_email_relationship(contact_list_id, contact_email_id, time_created) VALUES('$contact_list_id', '$email_account_id', '$time_created')");
							}
						}

						if($query) {
							echo json_encode(array('status' => 'success', 'status_code' => '001'));
						} else {
							echo json_encode(array('status' => 'error', 'status_code' => '002'));
						}

					/*
					} else {
						echo json_encode(array('status' => 'error', 'status_code' => '002', 'status_subcode' => '2001'));
					}

				} else {
					echo json_encode(array('status' => 'error', 'status_code' => '002'));
				}
			} else {
				echo json_encode(array('status' => 'error', 'status_code' => '002'));
			}
		} else {
			echo json_encode(array('status' => 'error', 'status_code' => '002'));
		}
		*/

	} else {
		echo json_encode(array('status' => 'error', 'status_code' => '003'));
	}

}

if(isset($data['action']) && $data['action'] == 'edit_contact_list') {

	$user_id = trim(strip_tags(mysqli_real_escape_string($conn, $data['user_id'])));
	$contact_list_id = trim(strip_tags(mysqli_real_escape_string($conn, $data['contact_list_id'])));
	$contact_list_title = trim(strip_tags(mysqli_real_escape_string($conn, $data['contact_list_title'])));
	$contact_list_description = trim(strip_tags(mysqli_real_escape_string($conn, $data['contact_list_description'])));

	if($user_id != '' && $contact_list_id != '' && $contact_list_title != '') {

		$query = mysqli_query($conn, "UPDATE contact_lists SET contact_list_title='$contact_list_title', contact_list_description='$contact_list_description' WHERE id='$contact_list_id' && user_id='$user_id'");
		if($query) {
			echo json_encode(array('status' => 'success', 'status_code' => '001'));
		} else {
			echo json_encode(array('status' => 'error', 'status_code' => '002'));
		}

	} else {
		echo json_encode(array('status' => 'error', 'status_code' => '003'));
	}

}

if(isset($data['action']) && $data['action'] == 'delete_contact_list') {

	$user_id = trim(strip_tags(mysqli_real_escape_string($conn, $data['user_id'])));
	$id = trim(strip_tags(mysqli_real_escape_string($conn, $data['id'])));

	if($user_id != '' && $id != '') {

		$query = mysqli_query($conn, "DELETE FROM contact_lists WHERE id='$id'");
		$contact_list_query = mysqli_query($conn, "DELETE FROM account_contact_list WHERE contact_id='$id'");

		if($query && $contact_list_query) {
			echo json_encode(array('status' => 'success', 'status_code' => '001'));
		} else {
			echo json_encode(array('status' => 'error', 'status_code' => '002'));
		}

	} else {
		echo json_encode(array('status' => 'success', 'status_code' => '002'));
	}

}

if(isset($data['action']) && $data['action'] == 'get_contact_emails') {

	$user_id = trim(strip_tags(mysqli_real_escape_string($conn, $data['user_id'])));
	
	if($user_id != '' && $user_id != 0) {

		if(isset($data['contact_list_id']) && $data['contact_list_id'] != '') {
			$contact_list_id = trim(strip_tags(mysqli_real_escape_string($conn, $data['contact_list_id'])));
			$query = mysqli_query($conn, "SELECT cea.id, cea.user_id, cea.admin_id, cea.email_address, cea.active_status, cea.time_created FROM contact_email_accounts AS cea LEFT JOIN contact_email_relationship AS cer ON cea.id=cer.contact_email_id WHERE (cea.user_id='$user_id' || cea.admin_id='$user_id') && cer.contact_list_id='$contact_list_id' && cea.delete_status='0' ORDER BY cea.id ASC");
		} else {
			$query = mysqli_query($conn, "SELECT cea.id, cea.user_id, cea.admin_id, cea.email_address, cea.active_status, cea.time_created FROM contact_email_accounts AS cea LEFT JOIN contact_email_relationship AS cer ON cea.id=cer.contact_email_id WHERE (cea.user_id='$user_id' || cea.admin_id='$user_id') && cea.delete_status='0' ORDER BY cea.id ASC");
		}

		$result = array();

		if(mysqli_num_rows($query) > 0) {
			while($row = mysqli_fetch_assoc($query)) {
				
				$email_account_id = $row['id'];
				$contact_list = '';
				$contact_list_query = mysqli_query($conn, "SELECT cl.contact_list_title FROM contact_email_relationship AS cer LEFT JOIN contact_lists AS cl ON cer.contact_list_id=cl.id WHERE cer.contact_email_id='$email_account_id' && cl.active_status='1' && cl.delete_status='0'");
				if(mysqli_num_rows($contact_list_query) > 0) {
					$i = 0;
					while($contact_list_result = mysqli_fetch_assoc($contact_list_query)) {
						$i++;
						if(mysqli_num_rows($contact_list_query) == 1) {
							$contact_list .= $contact_list_result['contact_list_title'];
						} else {
							if($i == 1) {
								$contact_list .= $contact_list_result['contact_list_title'];
							} else {
								$contact_list .= ", ".$contact_list_result['contact_list_title'];
							}
						}
					}
				}
				$result['id_'.$row['id']] = array(
					'id' => $row['id'], 
					'user_id' => $row['user_id'], 
					'email_address' => $row['email_address'],
					'contact_lists' => $contact_list, 
					'active_status' => $row['active_status'],
					'time_created' => $row['time_created']
				);
				
			}
			$records = array('display_records' => $result);
			echo json_encode(array_merge(array('status' => 'success', 'status_code' => '001', 'total_record' => mysqli_num_rows($query)), $records));
		} else {
			echo json_encode(array('status' => 'error', 'status_code' => '004', 'total_record' => mysqli_num_rows($query)));
		}

	}

}

if(isset($data['action']) && $data['action'] == 'single_contact_emails') {

	$id = trim(strip_tags(mysqli_real_escape_string($conn, $data['contact_email_id'])));
	$user_id = trim(strip_tags(mysqli_real_escape_string($conn, $data['user_id'])));

	if($id != '' && $id != 0 && $user_id != '' && $user_id != 0) {

		$query = mysqli_query($conn, "SELECT id, email_address FROM contact_email_accounts WHERE id='$id' && user_id='$user_id' && delete_status='0'");

		if(mysqli_num_rows($query) > 0) {
			$result = mysqli_fetch_assoc($query);

			$contact_list = '';
			$contact_list_query = mysqli_query($conn, "SELECT cl.id FROM contact_email_relationship AS acl LEFT JOIN contact_lists AS cl ON acl.contact_list_id=cl.id WHERE acl.contact_email_id='$id' && cl.active_status='1' && cl.delete_status='0'");
			if(mysqli_num_rows($contact_list_query) > 0) {
				$i = 0;
				while($contact_list_result = mysqli_fetch_assoc($contact_list_query)) {
					$i++;
					if(mysqli_num_rows($contact_list_query) == 1) {
						$contact_list .= $contact_list_result['id'];
					} else {
						if($i == 1) {
							$contact_list .= $contact_list_result['id'];
						} else {
							$contact_list .= ",".$contact_list_result['id'];
						}
					}
				}
			}

			$result['contact_lists'] = $contact_list;
			$records = array('display_records' => $result);
			echo json_encode(array_merge(array('status' => 'success', 'status_code' => '001', 'total_record' => mysqli_num_rows($query)), $records));
		} else {
			echo json_encode(array('status' => 'error', 'status_code' => '004', 'total_record' => mysqli_num_rows($query)));
		}

	} else {
		echo json_encode(array('status' => 'error', 'status_code' => '002'));
	}

}

if(isset($data['action']) && $data['action'] == 'edit_contact_email') {

	$user_id = trim(strip_tags(mysqli_real_escape_string($conn, $data['user_id'])));
	$email_address_id = trim(strip_tags(mysqli_real_escape_string($conn, $data['email_address_id'])));
	$email_address = trim(strip_tags(mysqli_real_escape_string($conn, $data['email_address'])));
	$contact_list = trim(strip_tags(mysqli_real_escape_string($conn, $data['contact_list'])));

	if($user_id != '' && $email_address_id != '' && $email_address != '') {

		$query = mysqli_query($conn, "UPDATE contact_email_accounts SET email_address='$email_address' WHERE id='$email_address_id' && user_id='$user_id'");

		$contact_list_array = explode(',', $contact_list);
		
		$contact_list_query = mysqli_query($conn, "DELETE FROM contact_email_relationship WHERE contact_email_id='$email_address_id' && delete_status='0'");

		if($contact_list != '') {
			if($contact_list_array > 0) {
				for($i = 0; $i < sizeof($contact_list_array); $i++) {
				
					$update_contact_list = mysqli_query($conn, "INSERT INTO contact_email_relationship(contact_list_id, contact_email_id, time_created) VALUES('{$contact_list_array[$i]}', '$email_address_id', '$time_created')");

				}
			}
		}

		if($query) {
			echo json_encode(array('status' => 'success', 'status_code' => '001'));
		} else {
			echo json_encode(array('status' => 'error', 'status_code' => '002'));
		}

	} else {
		echo json_encode(array('status' => 'error', 'status_code' => '003'));
	}

}

if(isset($data['action']) && $data['action'] == 'delete_contact_email') {

	$user_id = trim(strip_tags(mysqli_real_escape_string($conn, $data['user_id'])));
	$email_address_id = trim(strip_tags(mysqli_real_escape_string($conn, $data['email_address_id'])));

	if($user_id != '' && $email_address_id != '') {

		$query = mysqli_query($conn, "DELETE FROM email_accounts WHERE id='$email_address_id' && user_id='$user_id'");
		$account_contact_list_query = mysqli_query($conn, "DELETE FROM account_contact_list WHERE email_account_id='$email_address_id'");

		if($query && $account_contact_list_query) {
			echo json_encode(array('status' => 'success', 'status_code' => '001'));
		} else {
			echo json_encode(array('status' => 'error', 'status_code' => '002'));
		}

	} else {
		echo json_encode(array('status' => 'error', 'status_code' => '002'));
	}

}

if(isset($data['action']) && $data['action'] == 'new_contact_email') {

	$user_id = trim(strip_tags(mysqli_real_escape_string($conn, $data['user_id'])));
	$email_address = trim(strip_tags(mysqli_real_escape_string($conn, $data['email_address'])));
	$contact_list = trim(strip_tags(mysqli_real_escape_string($conn, $data['contact_list'])));

	if($user_id != '' && $email_address != '') {

		$query = mysqli_query($conn, "INSERT INTO email_accounts(user_id, email_address, time_created) VALUES('$user_id', '$email_address', '$time_created')");
		
		if($query) {

			$email_account_id = mysqli_insert_id($conn);

			if($contact_list != '') {
				$contact_list_array = explode(',', $contact_list);

				for($i = 0; $i < sizeof($contact_list_array); $i++) {

					$contact_list_query = mysqli_query($conn, "INSERT INTO account_contact_list(contact_id, email_account_id, time_created) VALUES('{$contact_list_array[$i]}', '$email_account_id', '$time_created')");

				}
			}

			echo json_encode(array('status' => 'success', 'status_code' => '001'));
		} else {
			echo json_encode(array('status' => 'error', 'status_code' => '002'));
		}

	} else {
		echo json_encode(array('status' => 'error', 'status_code' => '003'));
	}

}

if(isset($data['action']) && $data['action'] == 'get_campaigns') {

	$user_id = trim(strip_tags(mysqli_real_escape_string($conn, $data['user_id'])));

	if(isset($data['staff_id']) && $data['staff_id'] != '') {
		$staff_id = trim(strip_tags(mysqli_real_escape_string($conn, $data['staff_id'])));
	} else {
		$staff_id = '';
	}

	if($user_id != '' && $user_id != 0) {

		if(isset($data['campaign_type']) && $data['campaign_type'] == 'schedule_campaign') {
			if($staff_id == '') {
				$query = mysqli_query($conn, "SELECT c.id, c.user_id, c.admin_id, c.account_id, c.contact_list_id, c.campaign_title, c.subject, c.is_completed, c.active_status, c.time_created FROM campaigns AS c LEFT JOIN campaign_meta AS cm ON c.id=cm.campaign_id WHERE c.user_id='$user_id' && c.admin_id='$user_id' && c.delete_status='0' && (cm.meta_key='is_schedule' && cm.meta_value='1') ORDER BY c.time_created DESC");
			} else {
				$query = mysqli_query($conn, "SELECT c.id, c.user_id, c.admin_id, c.account_id, c.contact_list_id, c.campaign_title, c.subject, c.is_completed, c.active_status, c.time_created FROM campaigns AS c LEFT JOIN campaign_meta AS cm ON c.id=cm.campaign_id WHERE c.user_id='$staff_id' && c.admin_id='$user_id' && c.delete_status='0' && (cm.meta_key='is_schedule' && cm.meta_value='1') ORDER BY c.time_created DESC");
			}
		} else {
			if($staff_id == '') {
				$query = mysqli_query($conn, "SELECT id, user_id, account_id, contact_list_id, campaign_title, subject, is_completed, active_status, time_created FROM campaigns WHERE admin_id='$user_id' && user_id='$user_id' && delete_status='0' ORDER BY time_created DESC");
			} else {
				$query = mysqli_query($conn, "SELECT id, user_id, account_id, contact_list_id, campaign_title, subject, is_completed, active_status, time_created FROM campaigns WHERE admin_id='$user_id' && user_id='$staff_id' && delete_status='0' ORDER BY time_created DESC");
			}
		}

		if(mysqli_num_rows($query) > 0) {
			while($row = mysqli_fetch_assoc($query)) {
				$campaign_id = $row['id'];
				$result['id_'.$row['id']] = array(
					'id' => $row['id'], 
					'user_id' => $row['user_id'], 
					'campaign_title' => $row['campaign_title'], 
					'subject' => $row['subject'], 
					'is_completed' => $row['is_completed'], 
					'active_status' => ($row['active_status'] == 2) ? date('d-M-Y H:i:s A', campaign_meta($campaign_id, 'schedule_time')) : $row['active_status'], 
					'time_created' => $row['time_created']
				);
				
			}
			$records = array('display_records' => $result);
			echo json_encode(array_merge(array('status' => 'success', 'status_code' => '001', 'total_record' => mysqli_num_rows($query)), $records));
		} else {
			echo json_encode(array('status' => 'error', 'status_code' => '004', 'total_record' => mysqli_num_rows($query)));
		}

	}

}

if(isset($data['action']) && $data['action'] == 'single_campaign') {

	$user_id = trim(strip_tags(mysqli_real_escape_string($conn, $data['user_id'])));
	$id = trim(strip_tags(mysqli_real_escape_string($conn, $data['id'])));

	if($user_id != '' && $user_id != 0 && $id != '' && $id != 0) {

		$query = mysqli_query($conn, "SELECT id, user_id, account_id, contact_list_id, campaign_title, campaign_description, subject, content, is_completed, active_status, time_created FROM campaigns WHERE id='$id' && user_id='$user_id' && delete_status='0'");

		if(mysqli_num_rows($query) > 0) {
			while($row = mysqli_fetch_assoc($query)) {
				$campaign_id = $row['id'];
				$result = array(
					'id' => $row['id'], 
					'user_id' => $row['user_id'], 
					'account_id' => $row['account_id'], 
					'contact_list_id' => $row['contact_list_id'], 
					'campaign_title' => $row['campaign_title'], 
					'campaign_description' => $row['campaign_description'], 
					'subject' => $row['subject'], 
					'content' => html_entity_decode(htmlspecialchars_decode($row['content'])), 
					'is_completed' => $row['is_completed'], 
					'active_status' => ($row['active_status'] == 2) ? date('d-M-Y H:i:s A', campaign_meta($campaign_id, 'schedule_time')) : $row['active_status'], 
					'time_created' => $row['time_created']
				);
				
				$meta_result = array(
					'is_schedule' => campaign_meta($campaign_id, 'is_schedule'), 
					'schedule_time' => (campaign_meta($campaign_id, 'schedule_time') == '' ? '' : date('d-m-Y H:i', campaign_meta($campaign_id, 'schedule_time'))), 
					'campaign_contact_details' => json_decode(campaign_meta($campaign_id, 'campaign_contact_details'), true), 
					'campaign_dynamic_data' => json_decode(campaign_meta($campaign_id, 'campaign_dynamic_data'), true), 
					'campaign_attachment_details' => campaign_meta($campaign_id, 'campaign_attachment_details'), 
					'campaign_links_redirects' => campaign_meta($campaign_id, 'campaign_links_redirects')
				);
				$result = array_merge($meta_result, $result);
				
			}
			$records = array('display_records' => $result);
			echo json_encode(array_merge(array('status' => 'success', 'status_code' => '001', 'total_record' => mysqli_num_rows($query)), $records));
		} else {
			echo json_encode(array('status' => 'error', 'status_code' => '004', 'total_record' => mysqli_num_rows($query)));
		}

	}

}

if(isset($data['action']) && $data['action'] == 'new_campaign') {

	$user_id = trim(strip_tags(mysqli_real_escape_string($conn, $data['user_id'])));
	$campaign_title = trim(strip_tags(mysqli_real_escape_string($conn, $data['campaign_title'])));
	$campaign_description = trim(strip_tags(mysqli_real_escape_string($conn, $data['campaign_description'])));
	$contact_list_id = trim(strip_tags(mysqli_real_escape_string($conn, $data['contact_list_id'])));
	$account_id = trim(strip_tags(mysqli_real_escape_string($conn, $data['account_id'])));
	$subject = trim(strip_tags(mysqli_real_escape_string($conn, $data['subject'])));
	$content = htmlentities(htmlspecialchars($data['content']));
	$href_links = trim(strip_tags(mysqli_real_escape_string($conn, $data['href_links'])));
	$attachments = trim(strip_tags(mysqli_real_escape_string($conn, $data['attachments'])));
	$is_schedule = trim(strip_tags(mysqli_real_escape_string($conn, $data['is_schedule'])));
	$schedule_time = trim(strtotime(strip_tags(mysqli_real_escape_string($conn, $data['schedule_time']))));
	$user_role = json_decode(user_meta($user_id, 'user_role'), true);


	if($user_role['campaign']['add'] == 1) {
		if(($user_id != '' && $campaign_title != '' && $contact_list_id != '' && $account_id != '' && $subject != '') || ($is_schedule != 0 && $schedule_time != '')) {

			$admin_id_query = mysqli_query($conn, "SELECT admin_id FROM users WHERE id='$user_id' && active_status='1' && delete_status='0'");
			if(mysqli_num_rows($admin_id_query) > 0) {
				$admin_id_result = mysqli_fetch_assoc($admin_id_query);
				$admin_id = $admin_id_result['admin_id'] ?? $user_id;
			} else {
				$admin_id = $user_id;
			}
		
			$user_meta_query = mysqli_query($conn, "SELECT subscription_id FROM users WHERE id='$user_id' && active_status='1' && delete_status='0'");

			if(mysqli_num_rows($user_meta_query) > 0) {
				$user_meta_result = mysqli_fetch_assoc($user_meta_query);
				$subscription_id = $user_meta_result['subscription_id'];

				$subscription_query = mysqli_query($conn, "SELECT * FROM subscriptions WHERE id='$subscription_id' && active_status='1' && delete_status='0'");
				if(mysqli_num_rows($subscription_query) > 0) {
					$subscription_meta_query = mysqli_query($conn, "SELECT meta_value FROM subscription_meta WHERE subscription_id='$subscription_id' && meta_key='features'");
					if(mysqli_num_rows($subscription_meta_query) > 0) {

						$subscription_meta_ = mysqli_fetch_assoc($subscription_meta_query);
						$subscription_meta_result = json_decode($subscription_meta_['meta_value'], true);

						if(mysqli_num_rows(mysqli_query($conn, "SELECT *, MONTH('date_created')=MONTH(NOW()) FROM campaigns WHERE user_id='$user_id'")) < $subscription_meta_result['campaigns']['limit']) {

							$campaign_contact_details = array();
							$contact_list_query = mysqli_query($conn, "SELECT * FROM contact_lists WHERE id='$contact_list_id' && user_id='$user_id' && active_status='1' && delete_status='0'");
							if(mysqli_num_rows($contact_list_query) > 0) {

								$contact_list_result = mysqli_fetch_assoc($contact_list_query);
								$campaign_contact_details['contact_list_title'] = $contact_list_result['contact_list_title'];
								$campaign_contact_details['contact_list_description'] = $contact_list_result['contact_list_description'];

								$contact_email_query = mysqli_query($conn, "SELECT cea.email_address FROM contact_email_accounts AS cea LEFT JOIN contact_email_relationship AS cer ON cea.id=cer.contact_email_id WHERE cer.contact_list_id='$contact_list_id' && (cea.user_id='$user_id' || cea.admin_id='$user_id') && cea.active_status='1' && cea.delete_status='0' && cer.active_status='1' && cer.delete_status='0'");
								if(mysqli_num_rows($contact_email_query) > 0) {
									$i = 0;
									while($contact_email_result = mysqli_fetch_assoc($contact_email_query)) {
										$campaign_contact_details['contact_email_accounts'][$i] = $contact_email_result['email_address'];
										$i++;
									}
								}

							}
							$campaign_contact_details = json_encode($campaign_contact_details);

							if($is_schedule == 1) {
								$active_status = 2;
							} else {
								$active_status = 1;
							}

							$query = mysqli_query($conn, "INSERT INTO campaigns(user_id, admin_id, account_id, contact_list_id, campaign_title, campaign_description, subject, content, active_status, time_created) VALUES('$user_id', '$admin_id', '$account_id', '$contact_list_id', '$campaign_title', '$campaign_description', '$subject', '$content', '$active_status', '$time_created')");
							$campaign_id = mysqli_insert_id($conn);
							$campaign_meta_query = mysqli_query($conn, "INSERT INTO campaign_meta(campaign_id, meta_key, meta_value) VALUES('$campaign_id', 'is_schedule', '$is_schedule'), ('$campaign_id', 'schedule_time', '$schedule_time'), ('$campaign_id', 'campaign_contact_details', '$campaign_contact_details'), ('$campaign_id', 'campaign_completed_contact_details', ''), ('$campaign_id', 'campaign_dynamic_data', ''), ('$campaign_id', 'campaign_attachment_details', '$attachments'), ('$campaign_id', 'campaign_links_redirects', '$href_links')");
							
							if($query && $campaign_meta_query) {
								echo json_encode(array('status' => 'success', 'status_code' => '001'));
							} else {
								echo json_encode(array('status' => 'error', 'status_code' => '002'));
							}

						} else {
							echo json_encode(array('status' => 'error', 'status_code' => '002', 'status_subcode' => '2001'));
						}

					} else {
						echo json_encode(array('status' => 'error', 'status_code' => '002'));
					}
				} else {
					echo json_encode(array('status' => 'error', 'status_code' => '002'));
				}
			} else {
				echo json_encode(array('status' => 'error', 'status_code' => '002'));
			}

		}
	} else {
		echo json_encode(array('status' => 'error', 'status_code' => '006'));
	}

}

if(isset($data['action']) && $data['action'] == 'edit_campaign') {

	$campaign_id = trim(strip_tags(mysqli_real_escape_string($conn, $data['campaign_id'])));
	$user_id = trim(strip_tags(mysqli_real_escape_string($conn, $data['user_id'])));
	$campaign_title = trim(strip_tags(mysqli_real_escape_string($conn, $data['campaign_title'])));
	$campaign_description = trim(strip_tags(mysqli_real_escape_string($conn, $data['campaign_description'])));
	$contact_list_id = trim(strip_tags(mysqli_real_escape_string($conn, $data['contact_list_id'])));
	$account_id = trim(strip_tags(mysqli_real_escape_string($conn, $data['account_id'])));
	$subject = trim(strip_tags(mysqli_real_escape_string($conn, $data['subject'])));
	$content = htmlentities(htmlspecialchars($data['content']));
	$href_links = trim(strip_tags(mysqli_real_escape_string($conn, $data['href_links'])));
	$attachments = trim(strip_tags(mysqli_real_escape_string($conn, $data['attachments'])));
	$is_schedule = trim(strip_tags(mysqli_real_escape_string($conn, $data['is_schedule'])));
	$schedule_time = trim(strtotime(strip_tags(mysqli_real_escape_string($conn, $data['schedule_time']))));

	if(($campaign_id != '' && $user_id != '' && $campaign_title != '' && $contact_list_id != '' && $account_id != '' && $subject != '') || ($is_schedule != 0 && $schedule_time != '')) {

		$campaign_contact_details = array();
		$contact_list_query = mysqli_query($conn, "SELECT * FROM contact_lists WHERE id='$contact_list_id' && user_id='$user_id' && active_status='1' && delete_status='0'");
		if(mysqli_num_rows($contact_list_query) > 0) {

			$contact_list_result = mysqli_fetch_assoc($contact_list_query);
			$campaign_contact_details['contact_list_title'] = $contact_list_result['contact_list_title'];
			$campaign_contact_details['contact_list_description'] = $contact_list_result['contact_list_description'];

			$contact_email_query = mysqli_query($conn, "SELECT e.email_address FROM email_accounts AS e LEFT JOIN account_contact_list AS ac ON e.id=ac.email_account_id WHERE ac.contact_id='$contact_list_id' && e.user_id='$user_id' && e.active_status='1' && e.delete_status='0' && ac.active_status='1' && ac.delete_status='0'");
			if(mysqli_num_rows($contact_email_query) > 0) {
				$i = 0;
				while($contact_email_result = mysqli_fetch_assoc($contact_email_query)) {
					$campaign_contact_details['contact_email_accounts'][$i] = $contact_email_result['email_address'];
					$i++;
				}
			}

		}
		$campaign_contact_details = json_encode($campaign_contact_details);

		if($is_schedule == 1) {
			$active_status = 2;
		} else {
			$active_status = 1;
		}

		$query = mysqli_query($conn, "UPDATE campaigns SET account_id='$account_id', contact_list_id='$contact_list_id', campaign_title='$campaign_title', campaign_description='$campaign_description', subject='$subject', content='$content', active_status='$active_status' WHERE id='$campaign_id' && user_id='$user_id'");
		
		$campaign_meta_query = mysqli_query($conn, "UPDATE campaign_meta SET meta_value= CASE
				WHEN meta_key='is_schedule' THEN '$is_schedule'
				WHEN meta_key='schedule_time' THEN '$schedule_time'
				WHEN meta_key='campaign_contact_details' THEN '$campaign_contact_details'
				WHEN meta_key='campaign_attachment_details' THEN '$attachments'
				WHEN meta_key='campaign_links_redirects' THEN '$href_links'
				ELSE `meta_value`
				END
			WHERE campaign_id='$campaign_id'");
		
		if($query && $campaign_meta_query) {
			echo json_encode(array('status' => 'success', 'status_code' => '001'));
		} else {
			echo json_encode(array('status' => 'error', 'status_code' => '002'));
		}

	}

}

if(isset($data['action']) && $data['action'] == 'delete_campaign') {
	
	$campaign_id = trim(strip_tags(mysqli_real_escape_string($conn, $data['campaign_id'])));
	$user_id = trim(strip_tags(mysqli_real_escape_string($conn, $data['user_id'])));

	if($campaign_id != '' && $campaign_id != 0 && $user_id != '' && $user_id != 0) {

		$query = mysqli_query($conn, "UPDATE campaigns SET delete_status='1' WHERE id='$campaign_id'");
		
		if($query) {
			echo json_encode(array('status' => 'success', 'status_code' => '001'));
		} else {
			echo json_encode(array('status' => 'error', 'status_code' => '002'));
		}

	}

}

if(isset($data['action']) && $data['action'] == 'get_staff_accounts') {

	$user_id = trim(strip_tags(mysqli_real_escape_string($conn, $data['user_id'])));

	if($user_id != '' && $user_id != 0) {

		$query = mysqli_query($conn, "SELECT * FROM users WHERE admin_id='$user_id' && role='1' && type='1' && delete_status='0'");
		$i = 0;

		if(mysqli_num_rows($query) > 0) {

			while($row = mysqli_fetch_assoc($query)) {

				$staff_id = $row['id'];
				$result['id_'.$row['id']] = array(
					'id' => $row['id'], 
					'staff_name' => user_meta($staff_id, 'first_name').' '.user_meta($staff_id, 'last_name'), 
					'accounts_total' => mysqli_num_rows(mysqli_query($conn, "SELECT * FROM accounts WHERE user_id='$staff_id' && admin_id='$user_id' && delete_status='0'")), 
					'campaigns_total' => mysqli_num_rows(mysqli_query($conn, "SELECT * FROM campaigns WHERE user_id='$staff_id' && admin_id='$user_id' && delete_status='0'")), 
					'active_status' => $row['active_status'], 
					'time_created' => $row['time_created']
				);
				
			$records = array('display_records' => $result);
			echo json_encode(array_merge(array('status' => 'success', 'status_code' => '001', 'total_record' => mysqli_num_rows($query)), $records));

			}

		} else {
			echo json_encode(array('status' => 'error', 'status_code' => '004', 'total_record' => mysqli_num_rows($query)));
		}

	}

}

if(isset($data['action']) && $data['action'] == 'new_staff') {

	$user_id = trim(strip_tags(mysqli_real_escape_string($conn, $data['user_id'])));
	$staff_first_name = trim(strip_tags(mysqli_real_escape_string($conn, $data['staff_first_name'])));
	$staff_last_name = trim(strip_tags(mysqli_real_escape_string($conn, $data['staff_last_name'])));
	$display_name = $staff_first_name.' '.$staff_last_name;
	$staff_email = trim(strip_tags(mysqli_real_escape_string($conn, $data['staff_email'])));
	$staff_pass = password_hash(trim(strip_tags(mysqli_real_escape_string($conn, $data['staff_pass']))), PASSWORD_DEFAULT);
	$allow_add_accounts = trim(strip_tags(mysqli_real_escape_string($conn, $data['allow_add_accounts'])));
	$allow_add_campaign = trim(strip_tags(mysqli_real_escape_string($conn, $data['allow_add_campaign'])));

	if($user_id != '' && $staff_first_name != '' && $staff_last_name != '' && $staff_email != '' && $staff_pass != '') {

		$user_meta_query = mysqli_query($conn, "SELECT subscription_id, subscription_transaction_id FROM users WHERE id='$user_id' && active_status='1' && delete_status='0'");

		if(mysqli_num_rows($user_meta_query) > 0) {
			$user_meta_result = mysqli_fetch_assoc($user_meta_query);
			$subscription_id = $user_meta_result['subscription_id'];
			$subscription_transaction_id = $user_meta_result['subscription_transaction_id'];
			$business_name = user_meta($user_id, 'business_name');
			$website_url = user_meta($user_id, 'website_url');
			$staff_roles = json_encode(array(
				'accounts' => array(
					'add' => $allow_add_accounts, 
					'edit' => 1,
					'delete' => 1
				), 
				'campaigns' => array(
					'add' => $allow_add_campaign, 
					'edit' => 1,
					'delete' => 1
				), 
				'inbox' => array(
					'read' => 1,
					'schedule' => 1,
					'sent' => 1,
					'trash' => 1,
					'urgent_email' => 1,
					'tracking_email' => 1
				),
				'contact_lists' => array(
					'add' => 1,
					'edit' => 1,
					'delete' => 1
				),
				'templates' => array(
					'add' => 1,
					'edit' => 1,
					'delete' => 1
				)
			));

			$user_subscription_transaction = user_meta($user_id, 'user_subscription');
			$user_subscription_transaction_array = json_decode($user_subscription_transaction, true);

			if(time() < $user_subscription_transaction_array['next_payment_billing']) {

				$subscription_query = mysqli_query($conn, "SELECT * FROM subscriptions WHERE id='$subscription_id' && active_status='1' && delete_status='0'");
				if(mysqli_num_rows($subscription_query) > 0) {
					$subscription_meta_query = mysqli_query($conn, "SELECT meta_value FROM subscription_meta WHERE subscription_id='$subscription_id' && meta_key='features'");
					if(mysqli_num_rows($subscription_meta_query) > 0) {

						$subscription_meta_ = mysqli_fetch_assoc($subscription_meta_query);
						$subscription_meta_result = json_decode($subscription_meta_['meta_value'], true);

						if(mysqli_num_rows(mysqli_query($conn, "SELECT * FROM users WHERE admin_id='$user_id' && role='1' && type='1' && delete_status='0'")) < $subscription_meta_result['staff_accounts']['limit']) {

							$query = mysqli_query($conn, "INSERT INTO users(
								user_login, 
								user_pass, 
								user_email, 
								display_name, 
								subscription_id, 
								subscription_transaction_id, 
								admin_id, 
								role, 
								type, 
								time_created
							) VALUES(
								'$staff_email', 
								'$staff_pass', 
								'$staff_email', 
								'$display_name', 
								'$subscription_id', 
								'$subscription_transaction_id', 
								'$user_id', 
								'1', 
								'1', 
								'$time_created'
							)");
							$staff_id = mysqli_insert_id($conn);
							$staff_meta_query = mysqli_query($conn, "INSERT INTO user_meta(user_id, meta_key, meta_value) VALUES
								('$staff_id', 'first_name', '$staff_first_name'), 
								('$staff_id', 'last_name', '$staff_last_name'), 
								('$staff_id', 'user_phone', ''), 
								('$staff_id', 'session_token', '{}'), 
								('$staff_id', 'user_cart', '{}'), 
								('$staff_id', 'user_subscription', '$user_subscription_transaction'), 
								('$staff_id', 'business_name', '$business_name'), 
								('$staff_id', 'website_url', '$website_url'), 
								('$staff_id', 'street_address_1', ''), 
								('$staff_id', 'street_address_2', ''), 
								('$staff_id', 'city', ''), 
								('$staff_id', 'state', ''), 
								('$staff_id', 'zipcode', ''), 
								('$staff_id', 'country', ''), 
								('$staff_id', 'user_role', '$staff_roles')
							");

							if($query && $staff_meta_query) {
								echo json_encode(array('status' => 'success', 'status_code' => '001'));
							} else {
								echo json_encode(array('status' => 'error', 'status_code' => '002'));
							}

						} else {
							echo json_encode(array('status' => 'error', 'status_code' => '002', 'status_subcode' => '2001'));
						}
						
					} else {
						echo json_encode(array('status' => 'error', 'status_code' => '002'));
					}
				} else {
					echo json_encode(array('status' => 'error', 'status_code' => '002'));
				}
				
			} else {
				echo json_encode(array('status' => 'error', 'status_code' => '002', 'status_subcode' => '2002'));
			}
		} else {
			echo json_encode(array('status' => 'error', 'status_code' => '002'));
		}

	} else {
		echo json_encode(array('status' => 'error', 'status_code' => '003'));
	}

}

if(isset($data['action']) && $data['action'] == 'single_staff') {

	$user_id = trim(strip_tags(mysqli_real_escape_string($conn, $data['user_id'])));
	$id = trim(strip_tags(mysqli_real_escape_string($conn, $data['id'])));

	if($user_id != '' && $user_id != 0 && $id != '' && $id != 0) {

		$query = mysqli_query($conn, "SELECT id, user_login, user_email, display_name, active_status, time_created FROM users WHERE id='$id' && admin_id='$user_id' && delete_status='0' && role='1' && type='1'");

		if(mysqli_num_rows($query) > 0) {
			while($row = mysqli_fetch_assoc($query)) {
				$staff_id = $row['id'];
				$result = array(
					'id' => $row['id'], 
					'user_login' => $row['user_login'], 
					'user_email' => $row['user_email'], 
					'display_name' => $row['display_name'], 
					'active_status' => $row['active_status'], 
					'time_created' => $row['time_created']
				);
				
				$meta_result = array(
					'first_name' => user_meta($staff_id, 'first_name'), 
					'last_name' => user_meta($staff_id, 'last_name'), 
					'user_phone' => user_meta($staff_id, 'user_phone'), 
					'business_name' => user_meta($staff_id, 'business_name'), 
					'website_url' => user_meta($staff_id, 'website_url'), 
					'street_address_1' => user_meta($staff_id, 'street_address_1'), 
					'street_address_2' => user_meta($staff_id, 'street_address_2'), 
					'city' => user_meta($staff_id, 'city'), 
					'state' => user_meta($staff_id, 'state'), 
					'zipcode' => user_meta($staff_id, 'zipcode'), 
					'country' => user_meta($staff_id, 'country'), 
					'user_role' => json_decode(user_meta($staff_id, 'user_role'), true)
				);
				$result = array_merge($meta_result, $result);
				
			}
			$records = array('display_records' => $result);
			echo json_encode(array_merge(array('status' => 'success', 'status_code' => '001', 'total_record' => mysqli_num_rows($query)), $records));
		} else {
			echo json_encode(array('status' => 'error', 'status_code' => '004', 'total_record' => mysqli_num_rows($query)));
		}

	}

}

if(isset($data['action']) && $data['action'] == 'edit_staff') {

	$user_id = trim(strip_tags(mysqli_real_escape_string($conn, $data['user_id'])));
	$staff_id = trim(strip_tags(mysqli_real_escape_string($conn, $data['staff_id'])));
	$staff_first_name = trim(strip_tags(mysqli_real_escape_string($conn, $data['staff_first_name'])));
	$staff_last_name = trim(strip_tags(mysqli_real_escape_string($conn, $data['staff_last_name'])));
	$display_name = $staff_first_name.' '.$staff_last_name;
	$staff_email = trim(strip_tags(mysqli_real_escape_string($conn, $data['staff_email'])));
	$staff_pass = trim(strip_tags(mysqli_real_escape_string($conn, $data['staff_pass'])));
	$user_phone = trim(strip_tags(mysqli_real_escape_string($conn, $data['user_phone'])));
	$street_address_1 = trim(strip_tags(mysqli_real_escape_string($conn, $data['street_address_1'])));
	$street_address_2 = trim(strip_tags(mysqli_real_escape_string($conn, $data['street_address_2'])));
	$city = trim(strip_tags(mysqli_real_escape_string($conn, $data['city'])));
	$state = trim(strip_tags(mysqli_real_escape_string($conn, $data['state'])));
	$zipcode = trim(strip_tags(mysqli_real_escape_string($conn, $data['zipcode'])));
	$country = trim(strip_tags(mysqli_real_escape_string($conn, $data['country'])));
	$allow_add_accounts = trim(strip_tags(mysqli_real_escape_string($conn, $data['allow_add_accounts'])));
	$allow_add_campaign = trim(strip_tags(mysqli_real_escape_string($conn, $data['allow_add_campaign'])));

	if($user_id != '' && $user_id != 0 && $staff_id != '' && $staff_id != 0 && $staff_first_name != '' && $staff_last_name != '' && $staff_email != '') {

		$staff_roles = json_encode(array(
			'accounts' => array(
				'read' => 1, 
				'add' => $allow_add_accounts, 
				'edit' => 1,
				'delete' => 1
			), 
			'campaigns' => array(
				'read' => 1, 
				'add' => $allow_add_campaign, 
				'edit' => 1,
				'delete' => 1
			), 
			'inbox' => array(
				'read' => 1,
				'schedule' => 1,
				'sent' => 1,
				'trash' => 1,
				'urgent_email' => 1,
				'tracking_email' => 1
			),
			'contact_lists' => array(
				'read' => 1, 
				'add' => 1,
				'edit' => 1,
				'delete' => 1
			),
			'templates' => array(
				'read' => 1, 
				'add' => 1,
				'edit' => 1,
				'delete' => 1
			)
		));

		if($staff_pass == '') {
			$query = mysqli_query($conn, "UPDATE users SET user_email='$staff_email', display_name='$display_name' WHERE id='$staff_id' && admin_id='$user_id' && role='1' && type='1'");
		} else {
			$staff_pass = password_hash($staff_pass, PASSWORD_DEFAULT);
			$query = mysqli_query($conn, "UPDATE users SET user_pass='$staff_pass', user_email='$staff_email', display_name='$display_name' WHERE id='$staff_id' && admin_id='$user_id' && role='1' && type='1'");
		}
		$staff_meta_query = mysqli_query($conn, "UPDATE user_meta SET meta_value= CASE
				WHEN meta_key='first_name' THEN '$staff_first_name'
				WHEN meta_key='last_name' THEN '$staff_last_name'
				WHEN meta_key='user_phone' THEN '$user_phone'
				WHEN meta_key='street_address_1' THEN '$street_address_1'
				WHEN meta_key='street_address_2' THEN '$street_address_2'
				WHEN meta_key='city' THEN '$city'
				WHEN meta_key='state' THEN '$state'
				WHEN meta_key='zipcode' THEN '$zipcode'
				WHEN meta_key='country' THEN '$country'
				WHEN meta_key='user_role' THEN '$staff_roles'
				ELSE `meta_value`
				END
			WHERE user_id='$staff_id'");

		if($query && $staff_meta_query) {
			echo json_encode(array('status' => 'success', 'status_code' => '001'));
		} else {
			echo json_encode(array('status' => 'error', 'status_code' => '002'));
		}

	} else {
		echo json_encode(array('status' => 'error', 'status_code' => '003'));
	}

}

if(isset($data['action']) && $data['action'] == 'delete_staff_account') {

	$user_id = trim(strip_tags(mysqli_real_escape_string($conn, $data['user_id'])));
	$staff_id = trim(strip_tags(mysqli_real_escape_string($conn, $data['staff_id'])));

	if($user_id != '' && $user_id != 0 && $staff_id != '' && $staff_id != 0) {
		$query = mysqli_query($conn, "UPDATE users SET delete_status='1' WHERE id='$staff_id' && admin_id='$user_id' && role='1' && type='1'");
		if($query) {
			echo json_encode(array('status' => 'success', 'status_code' => '001'));
		} else {
			echo json_encode(array('status' => 'error', 'status_code' => '002'));
		}
	} else {
		echo json_encode(array('status' => 'error', 'status_code' => '003'));
	}

}


