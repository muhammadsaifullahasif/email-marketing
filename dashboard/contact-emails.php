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
							<h1 class="m-0 d-inline">Contact Emails</h1>
							<a href="#" id="add_contact_email_btn" class="btn btn-outline-dark btn-sm mb-3 ml-2" data-toggle="modal" data-target="#new_contact_email_modal">Add New</a>
						</div><!-- /.col -->
						<div class="col-sm-6">
							<ol class="breadcrumb float-sm-right">
								<li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
								<li class="breadcrumb-item active">Contact Emails</li>
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
										<h5 class="card-heading d-inline">Contact Emails</h5>
									</div>
									<div class="col-md-6">
									</div>
								</div>
								<div class="card-body dropdown" style="height: 400px; max-height: 100%; overflow-y: auto;">

									<div id="contact_emails_msg"></div>

									<div class="table-responsive">
										<table class="table table-striped table-hover table-sm">
											<thead>
												<tr>
													<th><input type="checkbox" name="group_input_main" class="group_input_main group_input"></th>
													<th>Email Address</th>
													<th>Contact List</th>
													<th>Status</th>
													<th>Action</th>
												</tr>
											</thead>
											<tbody id="display_contact_emails">
											</tbody>
										</table>
									</div>

								</div>
							</div>
						</div>
					</div>
					<!-- /.Campaigns -->

					<!-- New Contact Email Modal -->
					<div class="modal fade" id="new_contact_email_modal">
						<div class="modal-dialog modal-dialog-centered">
							<div class="modal-content">
								<div class="modal-header">
									<h5 class="modal-heading">New Contact Email</h5>
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
										<span aria-hidden="true">&times;</span>
									</button>
								</div>
								<div class="modal-body">
									<div id="new_contact_email_form_msg"></div>
									<form class="form" id="new_contact_email_form">
										<div class="mb-3">
											<label>Email Address:</label>
											<input type="email" class="form-control form-control-border" placeholder="Enter Title" id="new_contact_email_address" name="new_contact_email_address">
											<div id="new_contact_email_address_msg"></div>
										</div>
										<div class="mb-3">
											<label>Contact Lists:</label>
											<div id="new_contact_list_container"></div>
										</div>
										<button class="btn btn-primary btn-sm" id="new_contact_email_form_btn" name="new_contact_email_form_btn">Submit</button>
									</form>
								</div>
							</div>
						</div>
					</div>
					<!-- /.New Contact Email Modal -->

					<!-- Edit Contact Email Modal -->
					<div class="modal fade" id="edit_contact_email_modal">
						<div class="modal-dialog modal-dialog-centered">
							<div class="modal-content">
								<div class="modal-header">
									<h5 class="modal-heading">Edit Contact Email</h5>
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
										<span aria-hidden="true">&times;</span>
									</button>
								</div>
								<div class="modal-body">
									<div id="edit_contact_email_form_msg"></div>
									<form class="form" id="edit_contact_email_form">
										<input type="hidden" value="" id="contact_email_id" name="contact_email_id">
										<div class="mb-3">
											<label>Email Address:</label>
											<input type="email" class="form-control form-control-border" placeholder="Enter Title" id="contact_email_address" name="contact_email_address">
											<div id="contact_email_address_msg"></div>
										</div>
										<div class="mb-3">
											<label>Contact Lists:</label>
											<div id="edit_contact_list_container"></div>
										</div>
										<button class="btn btn-primary btn-sm" id="edit_contact_email_form_btn" name="edit_contact_email_form_btn">Submit</button>
									</form>
								</div>
							</div>
						</div>
					</div>
					<!-- /.Edit Contact Email Modal -->
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
			$('#add_contact_email_btn').hide();
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

		function display_contact_emails(contact_emails) {
			$('#display_contact_emails').html('');
			if(contact_emails.status_code == '001') {
				$.each(contact_emails.display_records, function(key, value) {
					var active_class, active_title;
					if(value.active_status == 1) {
						active_class = 'badge-success';
						active_title = 'Active';
					} else if(value.active_status == 0) {
						active_class = 'badge-danger';
						active_title = 'Inactive';
					}

					var edit_btn, delete_btn;

					if(user_info.user_role.contact_lists.edit == 1) {
						edit_btn = '<button data-id="'+ value.id +'" class="btn btn-primary btn-sm edit_contact_email_btn">Edit</button>';
					}

					if(user_info.user_role.contact_lists.delete == 1) {
						delete_btn = '<button data-id="'+ value.id +'" class="btn btn-danger delete_contact_email_btn btn-sm">Delete</button>';
					}

					$('#display_contact_emails').append(
						'<tr>' + 
							'<td><input data-id="'+ value.id +'" type="checkbox" class="group_input" name="group_input"></td>' + 
							'<td>'+ value.email_address +'</td>' + 
							'<td>'+ value.contact_lists +'</td>' + 
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
				$('#display_contact_emails').html("<tr><td colspan='5' class='text-center'>No Record Found</td></tr>");
			}
		}

		if(user_info.user_role.contact_lists.read == 1) {
			display_contact_emails(contact_emails(user_info));
		} else {
			$('#display_contact_emails').html("<tr><td colspan='5' class='text-center'>You're not allowed to read contact emails</td></tr>");
		}

		function display_contact_lists(output_id, checkbox_output, contact_list = '') {
			contact_lists = contact_lists_function(user_info);
			$('#'+ output_id).html('');
			if(contact_lists.status_code == '001') {
				if(contact_list != '') {
					var contact_list_array = contact_list.split(',');
				}
				$.each(contact_lists.display_records, function(key, value) {

					if(contact_list != '') {
						if(contact_list_array.find(contact_list_available)) {
							var contact_list_checked = 'checked';
						} else {
							var contact_list_checked = '';
						}

						function contact_list_available(contact_list) {
							if(contact_list == value.id) {
								return true;
							} else {
								return false;
							}
						}
					} else {
						var contact_list_checked = '';
					}

					if(value.active_status == 1) {
						$('#'+ output_id).append(
							'<div class="custom-control custom-checkbox">' + 
								'<input type="checkbox" value="'+ value.id +'" name="'+ checkbox_output +'" '+ contact_list_checked +' class="custom-control-input '+ checkbox_output +'" id="contact_list_'+ value.id +'">' + 
								'<label class="custom-control-label" for="contact_list_'+ value.id +'">'+ value.contact_list_title +'</label>' + 
							'</div>'
						);
					}

				});
			} else {
				$('#contact_list_container').html("No Contact List Found");
			}
		}

		$(document).on('click', '.edit_contact_email_btn', function(){
			var id = $(this).data('id');
			if(user_info.user_role.contact_lists.edit == 1) {
				if(id != '' && id != 0) {
					$('#edit_contact_email_modal').modal('show');
					$('#edit_contact_email_form_msg').removeClass('alert alert-danger alert-success').text('');
					$('#contact_email_address').removeClass('is-invalid is-valid').val();
					$('#contact_email_address_msg').removeClass('invalid-feedback valid-feedback').text('');
					$.ajax({
						url: api_url,
						type: 'POST',
						data: JSON.stringify({ 'action':'single_contact_emails', contact_email_id:id, user_id:user_info.id }),
						success: function(result) {
							if(result.status_code == '001') {
								$('#contact_email_id').val(id);
								$('#contact_email_address').val(result.display_records.email_address);
								display_contact_lists('edit_contact_list_container', 'edit_email_contact_list', result.display_records.contact_lists);
							}
						}
					});
				}
			} else {
				alert("You're not allowed to edit contact emails")
			}
		});

		$('#edit_contact_email_form').on('submit', function(e){
			e.preventDefault();

			// if(user_info.user_role.contact_lists.edit == 1) {
			var edit_email_contact_list = '', i = 0;
			var id = $('#contact_email_id').val();
			var contact_email_address = $('#contact_email_address').val();
			var email_contact_list = $('input[name="edit_email_contact_list"]:checked').each(function(){
				i++;
				if(i == 1) {
					edit_email_contact_list += this.value;
				} else if(i > 1) {
					edit_email_contact_list += ','+ this.value;
				}
			});
			var bool = 0;

			if(contact_email_address == '') {
				$('#contact_email_address').removeClass('is-valid').addClass('is-invalid');
				$('#contact_email_address_msg').removeClass('valid-feedback').addClass('invalid-feedback').text('Please Enter Email Address');
				bool = 1;
			} else {
				$('#contact_email_address').removeClass('is-invalid is-valid');
				$('#contact_email_address_msg').removeClass('invalid-feedback valid-feedback').text('');
				bool = 0;
			}

			if(id == '') {
				$('#edit_contact_email_form_msg').removeClass('alert-success').addClass('alert alert-danger').text('Please reopen the edit form');
				bool = 1;
			} else {
				$('#edit_contact_email_form_msg').removeClass('alert alert-danger alert-success').text('');
				bool = 0;
			}

			if(bool == 0) {
				if(user_info.user_role.contact_lists.edit == 1) {
					$.ajax({
						url: api_url,
						type: 'POST',
						data: JSON.stringify({ action:'edit_contact_email', email_address_id:id, email_address:contact_email_address, contact_list:edit_email_contact_list, user_id:user_info.id }),
						success: function(result) {
							if(result.status_code == '001') {
								$('#edit_contact_email_form_msg').removeClass('alert-danger').addClass('alert alert-success').text('Email Account Successfully Updated');
								display_contact_emails(contact_emails(user_info));
								setTimeout(function(){
									$('#edit_contact_email_modal').modal('hide');
								}, 1000);
							} else if(result.status_code == '002') {
								$('#edit_contact_email_form_msg').removeClass('alert-success').addClass('alert alert-danger').text('Please Try Again');
							} else if(result.status_code == '003') {
								$('#edit_contact_email_form_msg').removeClass('alert-success').addClass('alert alert-danger').text('Please Fill Required Fields');
							}
						}
					});
				} else {
					alert("You're not allowed to edit contact emails");
				}
			}
			// }
		});

		$(document).on('click', '.delete_contact_email_btn', function(){
			var id = $(this).data('id');

			if(user_info.user_role.contact_lists.delete == 1) {
				if(id != '' && id != 0) {
					if(confirm('Are you sure to delete this?')) {
						$.ajax({
							url: api_url,
							type: 'POST',
							data: JSON.stringify({ action:'delete_contact_email', user_id:user_info.id, email_address_id:id }), 
							success: function(result) {
								if(result.status_code == '001') {
									$('#contact_emails_msg').removeClass('alert-danger').addClass('alert alert-success').text('Email Address Successfully Deleted');
									display_contact_emails(contact_emails(user_info));
								} else if(result.status_code == '002') {
									$('#contact_emails_msg').removeClass('alert-success').addClass('alert alert-danger').text('Please Try Again');
								}

								setTimeout(function(){
									$('#contact_emails_msg').removeClass('alert alert-danger alert-success').text('');
								}, 1000);
							}
						});
					}
				}
			} else {
				alert("You're not allowed to delete contact emails");
			}
		});

		$('#new_contact_email_modal').on('shown.bs.modal', function () {
			if(user_info.user_role.contact_lists.add == 1) {
				$('#new_contact_email_modal').modal('show');
			} else {
				alert("You're not allowed to add contact emails");
			}
			$('#new_contact_email_form_msg').removeClass('alert alert-danger alert-success').text('');
			$('#contact_email_address').removeClass('is-invalid is-valid');
			$('#contact_email_address_msg').removeClass('invalid-feedback valid-feedback').text('');
			display_contact_lists('new_contact_list_container', 'new_email_contact_list');
		});

		$('#new_contact_email_form').on('submit', function(e){
			e.preventDefault();

			// if(user_info.user_role.contact_lists.add == 1) {
			var new_contact_email_address = $('#new_contact_email_address').val();
			var new_email_contact_list = '', i = 0;
			var email_contact_list = $('input[name="new_email_contact_list"]:checked').each(function(){
				i++;
				if(i == 1) {
					new_email_contact_list += this.value;
				} else if(i > 1) {
					new_email_contact_list += ','+ this.value;
				}
			});
			var bool = 0;

			if(new_contact_email_address == '') {
				$('#new_contact_email_address').removeClass('is-valid').addClass('is-invalid');
				$('#new_contact_email_address_msg').removeClass('valid-feedback').addClass('invalid-feedback').text('Please Enter Email Address');
				bool = 1;
			} else {
				$('#new_contact_email_address').removeClass('is-invalid');
				$('#new_contact_email_address_msg').removeClass('invalid-feedback').text('');
				bool = 0;
			}

			if(bool == 0) {
				if(user_info.user_role.contact_lists.add == 1) {
					$.ajax({
						url: api_url,
						type: 'POST',
						data: JSON.stringify({ action:'new_contact_email', user_id:user_info.id, email_address:new_contact_email_address, contact_list:new_email_contact_list }),
						success: function(result) {
							if(result.status_code == '001') {
								$('#new_contact_email_form_msg').removeClass('alert-danger').addClass('alert alert-success').text('Email Address Successfully Created');
								setTimeout(function(){
									$('#new_contact_email_modal').modal('hide');
									display_contact_emails(contact_emails(user_info));
								}, 1000);
							} else if(result.status_code == '002') {
								$('#new_contact_email_form_msg').removeClass('alert-success').addClass('alert alert-danger').text('Please Try Again');
							} else if(result.status_code == '003') {
								$('#new_contact_email_form_msg').removeClass('alert-success').addClass('alert alert-danger').text('Please Fill Required Fields');
							}
						}
					});
				} else {
					alert("You're not allowed to add contact emails");
				}
			}

		});

	</script>
</body>
</html>
