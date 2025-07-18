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
							<h1 class="m-0 d-inline">New Staff</h1>
							<a href="staff-new.php" class="btn btn-outline-dark btn-sm mb-3 ml-2">Add New</a>
						</div><!-- /.col -->
						<div class="col-sm-6">
							<ol class="breadcrumb float-sm-right">
								<li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
								<li class="breadcrumb-item"><a href="staffs.php">Staffs</a></li>
								<li class="breadcrumb-item active">Staff New</li>
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
										<h5 class="card-heading d-inline">Staff New</h5>
									</div>
									<div class="col-md-6">
									</div>
								</div>
								<div class="card-body">
									<div id="new_staff_form_msg"></div>
									<form class="form" method="post" id="new_staff_form">
										<input type="hidden" value="new_staff" name="action">
										
										<div class="mb-3">
											<label>First Name:</label>
											<input type="text" class="form-control form-control-border" placeholder="Enter Staff First Name" name="staff_first_name" id="staff_first_name">
											<div id="staff_first_name_msg"></div>
										</div>

										<div class="mb-3">
											<label>Last Name:</label>
											<input type="text" class="form-control form-control-border" placeholder="Enter Staff Last Name" name="staff_last_name" id="staff_last_name">
											<div id="staff_last_name_msg"></div>
										</div>

										<div class="mb-3">
											<label>Staff Email:</label>
											<input type="text" class="form-control form-control-border" placeholder="Enter Staff Email" name="staff_email" id="staff_email">
											<div id="staff_email_msg"></div>
										</div>
										
										<div class="mb-3">
											<label>Password:</label>
											<input type="password" class="form-control form-control-border" placeholder="Enter Password" name="staff_pass" id="staff_pass">
											<div id="staff_pass_msg"></div>
										</div>

										<div class="custom-control custom-switch mb-3">
											<input type="checkbox" class="custom-control-input" id="allow_add_accounts">
											<label class="custom-control-label" for="allow_add_accounts">Allow Add Accounts</label>
										</div>

										<div class="custom-control custom-switch mb-3">
											<input type="checkbox" class="custom-control-input" id="allow_add_campaign">
											<label class="custom-control-label" for="allow_add_campaign">Allow Campaigns</label>
										</div>
										
										<button id="new_staff_form_btn" name="new_staff_form_btn" class="btn btn-dark" type="submit">Submit</button>
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

		$('#staff_first_name').on('focus', function(){
			$('#staff_first_name').removeClass('is-invalid');
			$('#staff_first_name_msg').removeClass('invalid-feedback').text('');
		});
		$('#staff_last_name').on('focus', function(){
			$('#staff_last_name').removeClass('is-invalid');
			$('#staff_last_name_msg').removeClass('invalid-feedback').text('');
		});
		$('#staff_email').on('focus', function(){
			$('#staff_email').removeClass('is-invalid');
			$('#staff_email_msg').removeClass('invalid-feedback').text('');
		});
		$('#staff_pass').on('focus', function(){
			$('#staff_pass').removeClass('is-invalid');
			$('#staff_pass_msg').removeClass('invalid-feedback').text('');
		});
		
		$('#new_staff_form').on('submit', function(e){
			e.preventDefault();
			var staff_first_name = $('#staff_last_name').val();
			var staff_last_name = $('#staff_last_name').val();
			var staff_email = $('#staff_email').val();
			var staff_pass = $('#staff_pass').val();
			if($('#allow_add_accounts').is(':checked')) {
				var allow_add_accounts = 1;
			} else {
				var allow_add_accounts = 0;
			}
			if($('#allow_add_campaign').is(':checked')) {
				var allow_add_campaign = 1;
			} else {
				var allow_add_campaign = 0;
			}
			var bool = 0;

			if(staff_first_name == '') {
				$('#staff_first_name').removeClass('is-valid').addClass('is-invalid');
				$('#staff_first_name_msg').removeClass('valid-feedback').addClass('invalid-feedback').text('Staff Name is Required');
				bool = 1;
			} else {
				$('#staff_first_name').removeClass('is-invalid');
				$('#staff_first_name_msg').removeClass('invalid-feedback').text('');
			}
			if(staff_last_name == '') {
				$('#staff_last_name').removeClass('is-valid').addClass('is-invalid');
				$('#staff_last_name_msg').removeClass('valid-feedback').addClass('invalid-feedback').text('Staff Name is Required');
				bool = 1;
			} else {
				$('#staff_last_name').removeClass('is-invalid');
				$('#staff_last_name_msg').removeClass('invalid-feedback').text('');
			}
			if(staff_email == '') {
				$('#staff_email').removeClass('is-valid').addClass('is-invalid');
				$('#staff_email_msg').removeClass('valid-feedback').addClass('invalid-feedback').text('Staff Email is Required');
				bool = 1;
			} else {
				$('#staff_email').removeClass('is-invalid');
				$('#staff_email_msg').removeClass('invalid-feedback').text('');
			}
			if(staff_pass == '') {
				$('#staff_pass').removeClass('is-valid').addClass('is-invalid');
				$('#staff_pass_msg').removeClass('valid-feedback').addClass('invalid-feedback').text('Staff Password is Required');
				bool = 1;
			} else {
				$('#staff_pass').removeClass('is-invalid');
				$('#staff_pass_msg').removeClass('invalid-feedback').text('');
			}

			if(bool == 0) {
				$.ajax({
					url: api_url,
					type: 'POST',
					beforeSend: function() {
						$('#new_staff_form_msg').removeClass('alert-danger').addClass('alert alert-success').text('Please Wait...');
						$('#new_staff_form_btn').addClass('disabled');
					},
					data: JSON.stringify({ 'action':'new_staff', 'user_id':user_info.id, 'staff_first_name':staff_first_name, 'staff_last_name':staff_last_name, 'staff_email':staff_email, 'staff_pass':staff_pass, 'allow_add_accounts':allow_add_accounts, 'allow_add_campaign':allow_add_campaign }),
					success: function(result) {
						if(result.status_code == '001') {
							$('#new_staff_form_msg').removeClass('alert-danger').addClass('alert alert-success').text('Staff Successfully Added, Please Wait...');
							setTimeout(function(){
								window.location.href = dashboard_url+'staffs.php';
							}, 1000);
						} else if(result.status_code == '002') {
							if(result.status_subcode == '2001') {
								$('#new_staff_form_msg').removeClass('alert-success').addClass('alert alert-danger').html("<a href='"+main_url+"'>Upgrade to Premium<i class='fas fa-crown ml-1'></i> Version</a>");
							} else if(result.status_subcode == '2002') {
								$('#new_staff_form_msg').removeClass('alert-success').addClass('alert alert-danger').html("Your package is expired, Please update your package.");
							} else {
								$('#new_staff_form_msg').removeClass('alert-success').addClass('alert alert-danger').text('Please Try Again');
							}
						} else if(result.status_code == '003') {
							$('#new_staff_form_msg').removeClass('alert-success').addClass('alert alert-danger').text('Please Fill Required Fields');
						}
						$('#new_staff_form_btn').removeClass('disabled');
					}
				});
			}
		});

	</script>
</body>
</html>
