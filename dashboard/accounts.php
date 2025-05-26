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
							<h1 class="m-0 d-inline">Accounts</h1>
							<a href="account-new.php" id="add_account_btn" class="btn btn-outline-dark btn-sm mb-3 ml-2">Add New</a>
						</div><!-- /.col -->
						<div class="col-sm-6">
							<ol class="breadcrumb float-sm-right">
								<li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
								<li class="breadcrumb-item active">Accounts</li>
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
										<form class="form-inline">
											<div class="col-4">
												<select class="form-control w-100 form-control-border">
													<option>Select Action</option>
													<option>Delete</option>
												</select>
											</div>
											<div class="col">
												<button class="btn btn-outline-dark">Apply</button>
											</div>
										</form>
									</div>
									<div class="col-md-6">
									</div>
								</div>
								<div class="card-body">
									<div class="table-responsive">
										<table class="table table-striped table-hover table-sm">
											<thead>
												<tr>
													<th><input type="checkbox" name="group_input_main" class="group_input_main group_input"></th>
													<th>Account Title</th>
													<th>Email Address</th>
													<th id="display_staff_name">Staff</th>
													<th>Status</th>
													<th>Action</th>
												</tr>
											</thead>
											<tbody id="display_accounts">
											</tbody>
										</table>
									</div>
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
			$('#add_account_btn').hide();
		}

		if(subscription_info.features.staff_accounts.limit == false) {
			$('#display_staff_name').hide();
		}

		$(document).on('change', '.group_input', function(e) {
			$(this).attr('checked', true);
			if($('.group_input_main').is(':checked')) {
				$('.group_input').attr('checked', 'checked');
			} else {
				$('.group_input').removeAttr('checked');
			}
			$(this).trigger('reset');
		});

		$(document).on('click', '.delete_account_btn', function(){
			var account_id = $(this).data('id');
			if(user_info.user_role.accounts.delete == 1) {
				if(account_id != '' && account_id != 0) {
					if(confirm('Are you sure to delete account?')) {
						$.ajax({
							url: api_url,
							type: 'POST',
							data: JSON.stringify({ 'action' : 'delete_account', 'account_id' : account_id }),
							success: function(result) {
								if(result.status_code == '001') {
									alert('Account Successfully Deleted');
									display_accounts();
								} else {
									alert('Please Try Again');
								}
							}
						});
					}
				}
			} else {
				alert("You're not allowed to delete accounts");
			}
		});

		function display_accounts() {
			account_info = account_info_function(user_info <?php if(isset($_GET['staff_id']) && $_GET['staff_id'] != '') { echo ", ".trim(strip_tags($_GET['staff_id'])); } ?>);
			$('#display_accounts').html('');
			if(account_info.status_code == '001') {
				$.each(account_info.display_records, function(key, value) {
					var active_class, active_title;
					if(value.verified_status != 0) {
						if(value.active_status == 1) {
							active_class = 'badge-success';
							active_title = 'Active';
						} else if(value.active_status == 0) {
							active_class = 'badge-danger';
							active_title = 'Inactive';
						}
					} else {
						active_class = 'badge-warning';
						active_title = 'Not Verified';
					}

					if(subscription_info.features.staff_accounts.limit != false) {
						var display_staff_name = "<td><a href='accounts.php?staff_id="+ value.user_id +"'>"+ value.staff_name +"</a>";
					} else {
						var display_staff_name = '';
						$('#display_staff_name').hide();
					}
					var edit_btn, delete_btn;

					if(user_info.user_role.accounts.edit == 1) {
						edit_btn = "<a href='account-edit.php?id="+ value.id +"' class='btn btn-primary btn-sm'>Edit</a>";
					}

					if(user_info.user_role.accounts.delete == 1) {
						delete_btn = "<button data-id='"+ value.id +"' class='btn btn-danger delete_account_btn btn-sm'>Delete</button>";
					}

					$('#display_accounts').append(
						'<tr>' + 
							'<td><input data-id="'+ value.id +'" type="checkbox" class="group_input" name="group_input"></td>' + 
							'<td>'+ value.account_title +'</td>' + 
							'<td><a href="#">'+ value.account_email +'</a></td>' + 
							display_staff_name + 
							'<td><span class="badge '+ active_class +'">'+ active_title +'</span></td>' + 
							'<td>' + 
								'<div class="btn-group">' + 
									edit_btn + 
									delete_btn + 
								'</div>' + 
							'</td>' + 
						'</tr>'
					);
				});
			} else {
				$('#display_accounts').html("<tr><td colspan='6' class='text-center'>No Record Found</td></tr>");
			}
		}

		if(user_info.user_role.accounts.read == 1) {
			display_accounts();
		} else {
			$('#display_accounts').html("<tr><td colspan='6' class='text-center'>You're not allowed to read accounts</td></tr>");
		}

	</script>

</body>
</html>
