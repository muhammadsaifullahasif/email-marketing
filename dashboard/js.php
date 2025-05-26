
<script src="plugins/jquery/jquery.min.js"></script>
<script type="text/javascript" src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="dist/js/adminlte.min.js"></script>
<script type="text/javascript" src="dist/js/main.js"></script>

<script type="text/javascript">
	
		<?php

		if(isset($_COOKIE['email_marketing_session_key'])) {
		?>
			$.ajax({
				url: api_url,
				type: 'POST',
				async: false,
				data: JSON.stringify({ 'action' : 'user_info', 'session_key' : '<?php echo $_COOKIE['email_marketing_session_key']; ?>' }),
				success: function(result) {
					user_info = result;
					// tmp = result;
				}
			});
		<?php
		} else {
		?>
			window.location.href = main_url + 'login.php';
		<?php
		}
		?>

		if(user_info.business_name != null) {
			$('#sidebar_display_name').text(user_info.business_name);
		} else {
			$('#sidebar_display_name').text(user_info.display_name);
		}

		var account_info_function = (user_info) => {
			var tmp;
			$.ajax({
				url: api_url,
				type: 'POST',
				async: false,
				data: JSON.stringify({ 'action' : 'get_accounts', 'user_id' : user_info.id }),
				success: function(result) {
					tmp = result;
					if(result.status_code == '004') {
						$('#display_emails').html("<tr><td class='text-center'><a href='account-new.php'>Click Here</a> to add account</td></tr>");
					}
				}
			});
			return tmp;
		}
		account_info = account_info_function(user_info);

		var single_account_info_function = (account_id, user_info) => {
			var tmp;
			$.ajax({
				url: api_url,
				type: 'POST',
				async: false,
				data: JSON.stringify({ 'action' : 'single_account', 'account_id' : account_id, 'user_id' : user_info.id }),
				success: function(result) {
					tmp = result;
				}
			});
			return tmp;
		}

		$.ajax({
			url: api_url,
			type: 'POST',
			async: false,
			data: JSON.stringify({ 'action' : 'single_subscription', 'subscription_id' : user_info.subscription_id }),
			success: function(result) {
				subscription_info = result;
			}
		});

		if(subscription_info.features.staff_accounts.limit == false) {
			$('#staff_menu').hide();
		}

		var templates_function = (user_info) => {
			var tmp;
			$.ajax({
				url: api_url,
				type: 'POST',
				async: false,
				data: JSON.stringify({ 'action' : 'get_templates', 'user_id' : user_info.id }),
				success: function(result) {
					tmp = result;
					if(result.status_code == '004') {
						$('#display_templates').html("<tr><td class='text-center'><a href='template-new.php'>Click Here</a> to add template</td></tr>");
					}
				}
			});
			return tmp;
		}
		
		var contact_lists_function = (user_info) => {
			var tmp;
			$.ajax({
				url: api_url,
				type: 'POST',
				async: false,
				data: JSON.stringify({ 'action' : 'get_contact_lists', 'user_id' : user_info.id }),
				success: function(result) {
					tmp = result;
					if(result.status_code == '004') {
						$('#display_contact_lists').html("<tr><td class='text-center' colspan='5'><a href='contact-new.php'>Click Here</a> to add template</td></tr>");
					}
				}
			});
			return tmp;
		}

</script>
