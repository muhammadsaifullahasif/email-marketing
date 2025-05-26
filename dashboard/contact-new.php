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
							<h1 class="m-0">Contact Lists New</h1>
						</div><!-- /.col -->
						<div class="col-sm-6">
							<ol class="breadcrumb float-sm-right">
								<li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
								<li class="breadcrumb-item"><a href="contacts.php">Contact Lists</a></li>
								<li class="breadcrumb-item active">Contact Lists New</li>
							</ol>
						</div><!-- /.col -->
					</div><!-- /.row -->
				</div><!-- /.container-fluid -->
			</div>
			<!-- /.content-header -->

			<!-- Main content -->
			<section class="content">
				<div class="container-fluid">

					<!-- Campaigns -->
					<div class="row">
						<div class="col-12">
							<div class="card">
								<div class="card-header row align-items-center justify-content-between">
									<div class="col-md-6">
										<h5 class="card-heading d-inline">Contact Lists New</h5>
									</div>
									<div class="col-md-6">
									</div>
								</div>
								<div class="card-body dropdown" style="height: 400px; max-height: 100%; overflow-y: auto;">

									<form class="form" id="new_contact_list_form" method="post">

										<div id="new_contact_list_form_msg"></div>
										
										<div class="mb-3">
											<label>Title:</label>
											<input type="text" class="form-control form-control-border" placeholder="Enter Title" id="contact_list_title" name="contact_list_title">
											<div id="contact_list_title_msg"></div>
										</div>

										<div class="mb-3">
											<label>Description:</label>
											<textarea class="form-control form-control-border" id="contact_list_description" name="contact_list_description" placeholder="Enter Description"></textarea>
										</div>

										<div class="mb-3">
											<label>Email Accounts:</label>
											<textarea class="form-control form-control-border" id="email_accounts_list" name="email_accounts_list" placeholder="Enter Email Accounts (Seperate with , )"></textarea>
											<div id="email_accounts_list_msg"></div>
										</div>

										<button class="btn btn-dark" id="new_contact_list_form_btn" name="new_contact_list_form_btn" type="submit">Submit</button>

									</form>

								</div>
							</div>
						</div>
					</div>
					<!-- /.Campaigns -->
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

		if(user_info.user_role.contact_lists.add == 0) {
			window.top.location = dashboard_url +'contacts.php';
		}

		$('#contact_list_title').on('focus', function(){
			$('#contact_list_title').removeClass('is-invalid is-valid');
			$('#contact_list_title_msg').removeClass('invalid-feedback valid-feedback').text('');
		});

		$('#new_contact_list_form').on('submit', function(e) {
			e.preventDefault();

			if(user_info.user_role.contact_lists.add == 1) {
				var contact_list_title = $('#contact_list_title').val();
				var bool = 0;

				if(contact_list_title == '') {
					$('#contact_list_title').addClass('is-invalid').removeClass('is-valid');
					$('#contact_list_title_msg').addClass('invalid-feedback').removeClass('valid-feedback').text('Please Enter Contact List Title');
					bool = 1;
				} else {
					$('#contact_list_title').removeClass('is-invalid');
					$('#contact_list_title_msg').removeClass('invalid-feedback').text('');
					bool = 0;
				}

				if(bool == 0) {

					if(user_info.user_role.contact_lists.add == 1) {

						$.ajax({
							url: api_url,
							type: 'POST',
							beforeSend: function() {
								$('#new_contact_list_form_msg').removeClass('alert-danger').addClass('alert alert-success').text('Please Wait...');
								$('#new_contact_list_form_btn').addClass('disabled');
							},
							data: JSON.stringify({ action:'new_contact_list', user_id:user_info.id, contact_list_title:contact_list_title, contact_list_description:$('#contact_list_description').val(), email_accounts_list:$('#email_accounts_list').val() }),
							success: function(result) {
								if(result.status_code == '001') {
									$('#new_contact_list_form_msg').removeClass('alert-danger').addClass('alert alert-success').text('Contact List Successfully Created, Please Wait...');
									setTimeout(function(){
										window.location.href = dashboard_url+'contacts.php';
									}, 1000);
								} else if(result.status_code == '002') {
									if(result.status_sub_code == '2001') {
										$('#new_contact_list_form_msg').removeClass('alert-success').addClass('alert alert-danger').html("<a href='"+main_url+"'>Upgrade to Premium<i class='fas fa-crown ml-1'></i> Version</a>");
									} else {
										$('#new_contact_list_form_msg').removeClass('alert-success').addClass('alert alert-danger').text('Please Try Again');
									}
								} else if(result.status_code == '003') {
									$('#new_contact_list_form_msg').removeClass('alert-success').addClass('alert alert-danger').text('Please Fill Required Fields');
								}
								$('#new_contact_list_form_btn').removeClass('disabled');
							}
						});

					} else {
						alert("You're not allowed to add contact list");
					}

				}
			}

		});

	</script>
</body>
</html>
