<html>
	<head>
		<title></title>
	</head>
	<body>
		
		<?php
		
		
		require_once('config.php');

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
								?>
								<p>Do you want to download the emails from server, <a href='#' id='download_emails_btn'>Click Here</a>, And if you don't want to download the emails from server, <a href='#' id='no_download_emails_btn'>Click Here</a></p>
								<?php
								// header('location: fetch_emails.php');
							}
						}
					}
				}
			}

		}
		
		
		?>

		<script src="https://code.jquery.com/jquery-3.6.3.min.js"></script>
		<script>
			$(document).ready(function(){
				var dashboard_url = '<?php echo $dashboard_url; ?>';
				$('#download_emails_btn').on('click', function(e){
					e.preventDefault();
					window.top.location = dashboard_url +'fetch_emails.php';
				});

				$('#no_download_emails_btn').on('click', function(e){
					e.preventDefault();
					window.top.location = dashboard_url +'skip_emails.php';
				});
			});
		</script>
	</body>
</html>

