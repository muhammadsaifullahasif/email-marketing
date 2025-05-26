
<script src="plugins/jquery/jquery.min.js"></script>
<script type="text/javascript" src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="dist/js/adminlte.min.js"></script>
<script type="text/javascript" src="dist/js/main.js"></script>

<script type="text/javascript">

	const home_url = '<?php echo $home_url; ?>';
	const dashboard_url = '<?php echo $dashboard_url; ?>';
	const api_url = '<?php echo $api_url; ?>';
	
		<?php

		if(isset($_COOKIE['email_marketing_session_key'])) {
		?>
			var user_info;
			$.ajax({
				url: api_url,
				type: 'POST',
				async: false,
				data: JSON.stringify({ 'action':'user_info', 'session_key':'<?php echo $_COOKIE['email_marketing_session_key']; ?>' }),
				success: function(result) {
					user_info = result;
				}
			});
			if(user_info.status_code == '003') {
				document.cookie = 'email_marketing_session_key=; Max-Age=0; path=/; domain=' + location.host;
				// window.location.href = home_url + 'login.php';
			}
		<?php
		} else {
		?>
			// window.location.href = home_url + 'login.php';
		<?php
		}
		?>

		if(user_info.user_role.inbox.sent == 0) {
			$('#compose_btn').hide();
		}

		if(user_info.user_role.inbox.read == 0 && user_info.user_role.inbox.schedule == 0 && user_info.user_role.inbox.sent == 0 && user_info.user_role.inbox.trash == 0 && user_info.user_role.inbox.urgent_email == 0 && user_info.user_role.inbox.tracking_email == 0) {
			$('#sidebar_inbox_btn, #sidebar_read_inbox_btn, #sidebar_starred_inbox_btn, #sidebar_sent_inbox_btn, #sidebar_draft_inbox_btn, #sidebar_important_inbox_btn, #sidebar_scheduled_inbox_btn, #sidebar_spam_inbox_btn, #sidebar_bin_inbox_btn').hide();
		}
		if(user_info.user_role.inbox.read == 0) {
			$('#sidebar_read_inbox_btn, #sidebar_starred_inbox_btn, #sidebar_important_inbox_btn, #sidebar_spam_inbox_btn, #sidebar_bin_inbox_btn').hide();
		}
		if(user_info.user_role.inbox.schedule == 0) {
			$('#sidebar_scheduled_inbox_btn').hide();
		}
		if(user_info.user_role.inbox.sent == 0) {
			$('#compose_btn, #sidebar_sent_inbox_btn, #sidebar_scheduled_inbox_btn').hide();
		}
		if(user_info.user_role.inbox.trash == 0) {
			$('#sidebar_bin_inbox_btn').hide();
		}
		if(user_info.user_role.inbox.urgent_email == 0) {
			$('#sidebar_important_inbox_btn').hide();
		}
		if(user_info.user_role.inbox.tracking_email == 0) {

		}

		if(user_info.user_role.contact_lists.read == 0 && user_info.user_role.contact_lists.add == 0) {
			$('#sidebar_read_contact_list_btn, #sidebar_contact_list_btn, #sidebar_add_contact_list_btn, #sidebar_contact_email_list_btn').hide();
		}
		if(user_info.user_role.contact_lists.read == 0) {
			$('#sidebar_contact_list_btn').hide();
		}
		if(user_info.user_role.contact_lists.add == 0) {
			$('#sidebar_add_contact_list_btn').hide();
		}

		if(user_info.user_role.campaigns.read == 0 && user_info.user_role.campaigns.add == 0) {
			$('#sidebar_read_campaign_btn, #sidebar_campaign_btn, #sidebar_add_campaign_btn, #sidebar_scheduled_campaign_btn, #sidebar_analysis_campaign_btn').hide();
		}
		if(user_info.user_role.campaigns.read == 0) {
			$('#sidebar_campaign_btn, #sidebar_scheduled_campaign_btn').hide();
		}
		if(user_info.user_role.campaigns.add == 0) {
			$('#sidebar_add_campaign_btn').hide();
		}

		if(user_info.user_role.templates.read == 0 && user_info.user_role.templates.add == 0) {
			$('#sidebar_read_template_btn, #sidebar_template_btn, #sidebar_add_template_btn').hide();
		}
		if(user_info.user_role.templates.read == 0) {
			$('#sidebar_template_btn').hide();
		}
		if(user_info.user_role.templates.add == 0) {
			$('#sidebar_add_template_btn').hide();
		}

		if(user_info.user_role.accounts.read == 0 && user_info.user_role.accounts.add == 0) {
			$('#sidebar_account_btn').hide();
		}
		if(user_info.user_role.accounts.read == 0) {
			$('#sidebar_read_account_btn').hide();
		}
		if(user_info.user_role.accounts.add == 0) {
			$('#sidebar_add_account_btn').hide();
		}

		if(user_info.business_name != null) {
			$('#sidebar_display_name').text(user_info.business_name);
		} else {
			$('#sidebar_display_name').text(user_info.display_name);
		}

		var account_info_function = (user_info, staff_id = '') => {
			var tmp;
			$.ajax({
				url: api_url,
				type: 'POST',
				async: false,
				data: JSON.stringify({ 'action' : 'get_accounts', 'user_id' : user_info.id <?php if(isset($_GET['staff_id']) && $_GET['staff_id'] != '' && $_GET['staff_id'] != 0) { echo ', staff_id:"'.$_GET['staff_id'].'"'; } ?> }),
				success: function(result) {
					tmp = result;
					if(result.status_code == '004') {
						$('#display_emails').html("<tr><td class='text-center'><a href='account-new.php'>Click Here</a> to add account</td></tr>");
					}
				}
			});
			return tmp;
		}

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

		if(user_info.status_code == '001') {
			$.ajax({
				url: api_url,
				type: 'POST',
				async: false,
				data: JSON.stringify({ 'action':'single_subscription', 'subscription_id':user_info.subscription_id }),
				success: function(result) {
					subscription_info = result;
				}
			});
		}

		if(subscription_info.features.staff_accounts.limit == false) {
			if(user_info.admin_id) {
				$('#sidebar_read_staff_btn').hide();
			}
		} else {
			if(user_info.admin_id) {
				$('#sidebar_read_staff_btn').hide();
			}
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
				data: JSON.stringify({ 'action':'get_contact_lists', 'user_id':user_info.id }),
				success: function(result) {
					tmp = result;
					if(result.status_code == '004') {
						$('#display_contact_lists').html("<tr><td class='text-center' colspan='5'><a href='contact-new.php'>Click Here</a> to add template</td></tr>");
					}
				}
			});
			return tmp;
		}

		var contact_emails = (user_info) => {
			var tmp;
			$.ajax({
				url: api_url,
				type: 'POST',
				async: false,
				data: JSON.stringify({ action:'get_contact_emails', user_id:user_info.id <?php if(isset($_GET['contact_list_id']) && $_GET['contact_list_id'] != '' && $_GET['contact_list_id'] != 0) { echo ', contact_list_id:'.$_GET['contact_list_id']; } ?> }),
				success: function(result) {
					tmp = result;
					if(result.status_code == '004') {
						$('#display_contact_lists').html("<tr><td class='text-center' colspan='5'><a href='contact-new.php'>Click Here</a> to add template</td></tr>");
					}
				}
			});
			return tmp;
		}

		var display_campaigns_function = (user_info, campaign_type = '', staff_id = '') => {
			var tmp;
			var campaign_data = '';
			if(campaign_type == 'schedule_campaign') {
				campaign_data += ', campaign_type:schedule_campaign';
			} else {
				campaign_data += '';
			}
			var staff_data = '';
			if(staff_id != '') {
				staff_data += ', staff_id:'+ staff_id;
			} else {
				staff_data += '';
			}
			$.ajax({
				url: api_url,
				type: 'POST',
				async: false,
				data: JSON.stringify({ 'action':'get_campaigns', 'user_id':user_info.id + campaign_data <?php if(isset($_GET['staff_id']) && $_GET['staff_id'] != '' && $_GET['staff_id'] != 0) { echo ', staff_id:"'.$_GET['staff_id'].'"'; } ?> }),
				success: function(result) {
					tmp = result;
					if(result.status_code == '004') {
						$('#display_campaigns').html("<tr><td class='text-center' colspan='6'><a href='contact-new.php'>Click Here</a> to add Campaign</td></tr>");
					}
				}
			});
			return tmp;
		}

		var staff_account_info_function = (user_info) => {
			var tmp;
			$.ajax({
				url: api_url,
				type: 'POST',
				async: false,
				data: JSON.stringify({ 'action':'get_staff_accounts', 'user_id':user_info.id }),
				success: function(result) {
					tmp = result;
					if(result.status_code == '004') {
						$('#display_staff_accounts').html("<tr><td class='text-center'><a href='staff-new.php'>Click Here</a> to add account</td></tr>");
					}
				}
			});
			return tmp;
		}

</script>
