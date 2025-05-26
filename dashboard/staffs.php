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
							<h1 class="m-0 d-inline">Staffs</h1>
							<a href="staff-new.php" class="btn btn-outline-dark btn-sm mb-3 ml-2">Add New</a>
						</div><!-- /.col -->
						<div class="col-sm-6">
							<ol class="breadcrumb float-sm-right">
								<li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
								<li class="breadcrumb-item active">Staffs</li>
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
													<th><input type="checkbox" name=""></th>
													<th>Name</th>
													<th>Accounts</th>
													<th>Campaigns</th>
													<th>Status</th>
													<th>Action</th>
												</tr>
											</thead>
											<tbody id="display_staff_accounts">
												<tr>
													<td class="text-center" colspan="6">No Record Found</td>
												</tr>
												<tr>
													<td><input type="checkbox" name=""></td>
													<td>Muhammad Saifullah Asif</td>
													<td><a href="#">10</a></td>
													<td><a href="#">20</a></td>
													<td><span class="badge badge-danger">Inactive</span></td>
													<td>
														<div class="btn-group">
															<button class="btn btn-primary btn-sm">Edit</button>
															<button class="btn btn-danger btn-sm">Delete</button>
														</div>
													</td>
												</tr>
												<tr>
													<td><input type="checkbox" name=""></td>
													<td>Muhammad Saifullah Asif</td>
													<td><a href="#">10</a></td>
													<td><a href="#">20</a></td>
													<td><span class="badge badge-warning">Not Verified</span></td>
													<td>
														<div class="btn-group">
															<button class="btn btn-primary btn-sm">Edit</button>
															<button class="btn btn-danger btn-sm">Delete</button>
														</div>
													</td>
												</tr>
												<tr>
													<td><input type="checkbox" name=""></td>
													<td>Muhammad Saifullah Asif</td>
													<td><a href="#">10</a></td>
													<td><a href="#">20</a></td>
													<td><span class="badge badge-success">Active</span></td>
													<td>
														<div class="btn-group">
															<button class="btn btn-primary btn-sm">Edit</button>
															<button class="btn btn-danger btn-sm">Delete</button>
														</div>
													</td>
												</tr>
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

		function display_staff_accounts() {
			staff_account_info = staff_account_info_function(user_info);
			$('#display_staff_accounts').html('');
			if(staff_account_info.status_code == '001') {
				$.each(staff_account_info.display_records, function(key, value) {
					var active_class, active_title;
					if(value.active_status == 1) {
						active_class = 'badge-success';
						active_title = 'Active';
					} else if(value.active_status == 0) {
						active_class = 'badge-danger';
						active_title = 'Inactive';
					}

					$('#display_staff_accounts').append(
						'<tr>' + 
							'<td><input data-id="'+ value.id +'" type="checkbox" class="group_input" name="group_input"></td>' + 
							'<td>'+ value.staff_name +'</td>' + 
							'<td><a href="accounts.php?staff_id='+ value.id +'">'+ value.accounts_total +'</a></td>' + 
							'<td><a href="campaigns.php?staff_id='+ value.id +'">'+ value.campaigns_total +'</a></td>' + 
							'<td><span class="badge '+ active_class +'">'+ active_title +'</span></td>' + 
							'<td>' + 
								'<div class="btn-group">' + 
									'<a href="staff-edit.php?id='+ value.id +'" class="btn btn-primary btn-sm">Edit</a>' + 
									'<button data-id="'+ value.id +'" class="btn btn-danger delete_staff_account_btn btn-sm">Delete</button>' + 
								'</div>' + 
							'</td>' + 
						'</tr>'
					);
				});
			} else {
				$('#display_staff_accounts').html("<tr><td colspan='6' class='text-center'>No Record Found</td></tr>");
			}
		}

		display_staff_accounts();

		$(document).on('change', '.group_input', function(e) {
			$(this).attr('checked', true);
			if($('.group_input_main').is(':checked')) {
				$('.group_input').attr('checked', 'checked');
			} else {
				$('.group_input').removeAttr('checked');
			}
			$(this).trigger('reset');
		});

		$(document).on('click', '.delete_staff_account_btn', function(){
			var staff_id = $(this).data('id');
			if(staff_id != '' && staff_id != 0) {
				if(confirm('Are you sure to delete staff account?')) {
					$.ajax({
						url: api_url,
						type: 'POST',
						data: JSON.stringify({ 'action':'delete_staff_account', 'user_id':user_info.id, 'staff_id':staff_id }),
						success: function(result) {
							if(result.status_code == '001') {
								alert('Staff Account Successfully Deleted');
								staff_account_info = staff_account_info_function(user_info);
								display_staff_accounts();
							} else {
								alert('Please Try Again');
							}
						}
					});
				}
			}
		});

	</script>
</body>
</html>
