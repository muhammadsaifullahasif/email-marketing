<?php

$conn = mysqli_connect('localhost', 'root', '', 'email_marketing');

session_start();

$user_id = $_SESSION['email_marketing_user_id'];

if(isset($_COOKIE['email_marketing_session_key']) && $user_id != '') {

	$query = mysqli_query($conn, "SELECT meta_value FROM user_meta WHERE meta_key='session_tokens' && user_id='$user_id'");
	if(mysqli_num_rows($query) > 0) {
		$result = mysqli_fetch_assoc($query);
		$session_tokens = json_decode($result['meta_value'], true);

		foreach($session_tokens as $key => $value) {
			if($value['session_key'] == $_COOKIE['email_marketing_session_key']) {
				?>
				<script type="text/javascript">
					var email_marketing_session_key = 'email_marketing_session_key';
					document.cookie = email_marketing_session_key + '=; expires=Thu, 01 Jan 1970 00:00:01 GMT;';
				</script>
				<?php
				unset($session_tokens[$key]);
				array_values($session_tokens);
			}
		}

		$session_tokens = json_encode($session_tokens);

		$update = mysqli_query($conn, "UPDATE user_meta SET meta_value='$session_tokens' WHERE meta_key='session_tokens' && user_id='$user_id'");

		if($update) {
			unset($_SESSION['email_marketing_user_id']);
			header('location: login.php');
		}
	}

}

?>