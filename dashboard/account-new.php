<?php include "head.php"; ?>

	<title>AdminLTE 3 | Starter</title>
</head>
<body class="hold-transition sidebar-mini">
	<div class="wrapper">

		<?php include "nav.php"; ?>

		<?php include "sidebar.php"; ?>

		<!-- Content Wrapper. Contains page content -->
		<div class="content-wrapper">
			<!-- Content Header (Page header) -->
			<div class="content-header">
				<div class="container-fluid">
					<div class="row mb-2">
						<div class="col-sm-6">
							<h1 class="m-0 d-inline">New Account</h1>
							<a href="account-new.php" id="add_account_btn" class="btn btn-outline-dark btn-sm mb-3 ml-2">Add New</a>
						</div><!-- /.col -->
						<div class="col-sm-6">
							<ol class="breadcrumb float-sm-right">
								<li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
								<li class="breadcrumb-item"><a href="accounts.php">Accounts</a></li>
								<li class="breadcrumb-item active">Account New</li>
							</ol>
						</div><!-- /.col -->
					</div><!-- /.row -->
				</div><!-- /.container-fluid -->
			</div>
			<!-- /.content-header -->

			<!-- Main content -->
			<section class="content">
				<div class="container-fluid">

					<!-- Account New -->
					<div class="row">
						<div class="col-12">
							<div class="card">
								<div class="card-header row align-items-center justify-content-between">
									<div class="col-md-6">
										<h5 class="card-heading d-inline">Account New</h5>
									</div>
									<div class="col-md-6">
									</div>
								</div>
								<div class="card-body">
									<form class="form" method="post" id="new_account_form">

										<div id="new_account_form_msg"></div>
										
										<div class="mb-3">
											<label>Account Title:</label>
											<input type="text" class="form-control form-control-border" placeholder="Enter Account Title" name="account_title" id="account_title">
											<div id="account_title_msg"></div>
										</div>

										<div class="mb-3">
											<label>Email:</label>
											<input type="email" class="form-control form-control-border" placeholder="Enter Email" name="account_email" id="account_email">
											<div id="account_email_msg"></div>
										</div>
										
										<div class="mb-3">
											<label>Password:</label>
											<input type="password" class="form-control form-control-border" placeholder="Enter Password" name="account_password" id="account_password">
											<div id="account_password_msg"></div>
										</div>

										<div id="staff_container"></div>
										
										<button class="btn btn-dark" id="new_account_form_btn" name="new_account_form_btn" type="submit">Submit</button>
									</form>
								</div>
							</div>
						</div>
					</div>
					<!-- /.Account New -->
				</div>
			</section>
			<!-- /.content -->
		</div>
		<!-- /.content-wrapper -->

		<?php include "footer.php"; ?>
	</div>
	<!-- ./wrapper -->

	<?php include "javascript.php"; ?>

	<script type="text/javascript">

		if(user_info.user_role.accounts.add == 0) {
			window.top.location = dashboard_url +'accounts.php';
			$('#add_account_btn').hide();
		}

		$('#account_title').on('focus', function(){
			$('#account_title').removeClass('is-invalid is-valid');
			$('#account_title_msg').removeClass('invalid-feedback valid-feedback').text('');
		});

		$('#account_email').on('focus', function(){
			$('#account_email').removeClass('is-invalid is-valid');
			$('#account_email_msg').removeClass('invalid-feedback valid-feedback').text('');
		});

		$('#account_password').on('focus', function(){
			$('#account_password').removeClass('is-invalid is-valid');
			$('#account_password_msg').removeClass('invalid-feedback valid-feedback').text('');
		});

		$('#new_account_form').on('submit', function(e){
			e.preventDefault();
			var account_title = $('#account_title').val();
			var account_email = $('#account_email').val();
			var account_password = $('#account_password').val();
			var staff_id = $('#staff_id').val();
			var bool = 0;

			if(account_title == '') {
				$('#account_title').removeClass('is-valid').addClass('is-invalid');
				$('#account_title_msg').removeClass('valid-feedback').addClass('invalid-feedback').text('Account Title Required!');
				bool = 1;
			} else {
				$('#account_title').removeClass('is-invalid is-valid');
				$('#account_title_msg').removeClass('invalid-feedback valid-feedback').text('');
				bool = 0;
			}

			if(account_email == '') {
				$('#account_email').removeClass('is-valid').addClass('is-invalid');
				$('#account_email_msg').removeClass('valid-feedback').addClass('invalid-feedback').text('Account Username Required!');
				bool = 1;
			} else {
				$('#account_email').removeClass('is-invalid is-valid');
				$('#account_email_msg').removeClass('invalid-feedback valid-feedback').text('');
				bool = 0;
			}

			if(account_password == '') {
				$('#account_password').removeClass('is-valid').addClass('is-invalid');
				$('#account_password_msg').removeClass('valid-feedback').addClass('invalid-feedback').text('Account Password Required!');
				bool = 1;
			} else {
				$('#account_password').removeClass('is-invalid is-valid');
				$('#account_password_msg').removeClass('invalid-feedback valid-feedback').text('');
				bool = 0;
			}

			if(bool == 0) {
				if(user_info.user_role.accounts.add == 1) {

					$.ajax({
						url: api_url,
						type: 'POST',
						beforeSend: function() {
							$('#new_account_form_msg').removeClass('alert-danger').addClass('alert alert-success').text('Please Wait...');
							$('#new_account_form_btn').addClass('disabled');
						},
						data: JSON.stringify({ 'action' : 'new_account', 'user_id' : user_info.id, 'staff_id':staff_id, 'account_title' : account_title, 'account_email' : account_email, 'account_password' : account_password }),
						success: function(result) {
							if(result.status_code == '001') {
								$('#new_account_form_msg').removeClass('alert-danger').addClass('alert alert-success').text('Account Successfully Added, Please Wait...');
								setTimeout(function(){
									window.location.href = dashboard_url+'accounts.php';
								}, 1000);
							} else if(result.status_code == '002') {
								if(result.status_subcode == '2001') {
									$('#new_account_form_msg').removeClass('alert-success').addClass('alert alert-danger').html("<a href='"+main_url+"'>Upgrade to Premium<i class='fas fa-crown ml-1'></i> Version</a>");
								} else if(result.status_subcode == '2002') {
									$('#new_account_form_msg').removeClass('alert-success').addClass('alert alert-danger').html("Your package is expired, Please update your package.");
								} else {
									$('#new_account_form_msg').removeClass('alert-success').addClass('alert alert-danger').text('Please Try Again');
								}
							} else if(result.status_code == '003') {
								$('#new_account_form_msg').removeClass('alert-success').addClass('alert alert-danger').text('Please Fill Required Fields');
							}
							$('#new_account_form_btn').removeClass('disabled');
						}
					});

				} else {
					alert("You're not allowed to add account");
				}
			}

		});

		var account_info = account_info_function(user_info);

		if(account_info.total_record == subscription_info.features.email_accounts.limit) {

			$('#new_account_form').html("<p class='text-center'><a href='http://localhost/email-marketing/'>Upgrade to Premium<i class='fas fa-crown ml-1 text-warning'></i> Version</a></p>");

		}

		if(subscription_info.features.staff_accounts.limit != false) {

			if(!user_info.admin_id) {
				var staff_account_info = staff_account_info_function(user_info);
				var staff_accounts = '';

				if(staff_account_info.status_code == '001') {
					$.each(staff_account_info.display_records, function(key, value) {
						if(value.active_status == 1) {
							staff_accounts += "<option value='"+ value.id +"'>"+ value.staff_name +"</option>";
						}
					});
				}

				$('#staff_container').html(
					"<div class='mb-3'>" + 
						"<label>Select Staff:</label>" + 
						"<select class='form-control form-control-border' name='staff_id' id='staff_id'>" + 
							"<option value=''>Select Staff</option>" + 
							staff_accounts + 
						"</select>" + 
					"</div>"
				);
			} else {
				$('#staff_container').html("<input type='hidden' value='' name='staff_id' id='staff_id'>");
			}

		} else {
			$('#staff_container').html("<input type='hidden' value='' name='staff_id' id='staff_id'>");
		}

	</script>
</body>
</html>
