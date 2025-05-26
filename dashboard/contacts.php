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
							<h1 class="m-0 d-inline">Contact Lists</h1>
							<a href="contact-new.php" id="add_contact_list_btn" class="btn btn-outline-dark btn-sm mb-3 ml-2">Add New</a>
						</div><!-- /.col -->
						<div class="col-sm-6">
							<ol class="breadcrumb float-sm-right">
								<li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
								<li class="breadcrumb-item active">Contact Lists</li>
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
										<h5 class="card-heading d-inline">Contact Lists</h5>
									</div>
									<div class="col-md-6">
									</div>
								</div>
								<div class="card-body dropdown" style="height: 400px; max-height: 100%; overflow-y: auto;">

									<div id="contacts_msg"></div>

									<div class="table-responsive">
										<table class="table table-striped table-hover table-sm">
											<thead>
												<tr>
													<th><input type="checkbox" name="group_input_main" class="group_input_main group_input"></th>
													<th>Title</th>
													<th>Total Contacts</th>
													<th>Status</th>
													<th>Action</th>
												</tr>
											</thead>
											<tbody id="display_contact_lists">
											</tbody>
										</table>
									</div>

								</div>
							</div>
						</div>
					</div>
					<!-- /.Campaigns -->

					<div class="modal fade" id="edit_contact_list_modal">
						<div class="modal-dialog modal-dialog-centered">
							<div class="modal-content">
								<div class="modal-header">
									<h5 class="modal-heading">Edit Contact List</h5>
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
										<span aria-hidden="true">&times;</span>
									</button>
								</div>
								<div class="modal-body">
									<div id="edit_contact_list_form_msg"></div>
									<form class="form" id="edit_contact_list_form">
										<input type="hidden" value="" id="contact_list_id" name="contact_list_id">
										<div class="mb-3">
											<label>Title:</label>
											<input type="text" class="form-control form-control-border" placeholder="Enter Title" id="contact_list_title" name="contact_list_title">
											<div id="contact_list_title_msg"></div>
										</div>
										<div class="mb-3">
											<label>Description:</label>
											<textarea class="form-control form-control-border" placeholder="Enter Description" id="contact_list_description" name="contact_list_description"></textarea>
										</div>
										<button class="btn btn-primary btn-sm" id="edit_contact_list_form_btn" name="edit_contact_list_form_btn">Submit</button>
									</form>
								</div>
							</div>
						</div>
					</div>
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
			
		$(document).on('change', '.group_input', function(e) {
			$(this).attr('checked', true);
			if($('.group_input_main').is(':checked')) {
				$('.group_input').attr('checked', 'checked');
			} else {
				$('.group_input').removeAttr('checked');
			}
			$(this).trigger('reset');
		});

		function display_contact_lists() {
			var contact_lists = contact_lists_function(user_info);
			$('#display_contact_lists').html('');
			if(contact_lists.status_code == '001') {
				$.each(contact_lists.display_records, function(key, value) {
					var active_class, active_title;
					if(value.active_status == 1) {
						active_class = 'badge-success';
						active_title = 'Active';
					} else if(value.active_status == 0) {
						active_class = 'badge-danger';
						active_title = 'Inactive';
					}

					var edit_btn = '', delete_btn = '';

					if(user_info.user_role.contact_lists.edit == 1) {
						edit_btn += '<button data-id="'+ value.id +'" class="btn btn-primary btn-sm edit_contact_list_btn">Edit</button>';
					}

					if(user_info.user_role.contact_lists.delete == 1) {
						delete_btn += '<button data-id="'+ value.id +'" class="btn btn-danger delete_contact_lists_btn btn-sm">Delete</button>';
					}

					$('#display_contact_lists').append(
						'<tr>' + 
							'<td><input data-id="'+ value.id +'" type="checkbox" class="group_input" name="group_input"></td>' + 
							'<td>'+ value.contact_list_title +'</td>' + 
							'<td><a href="contact-emails.php?contact_list_id='+ value.id +'">'+ value.total_contacts +'</a></td>' + 
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
				$('#display_contact_lists').html("<tr><td colspan='5' class='text-center'>No Record Found</td></tr>");
			}
		}

		if(user_info.user_role.contact_lists.read == 1) {
			display_contact_lists();
		} else {
			$('#display_contact_lists').html("<tr><td colspan='5' class='text-center'>You're not allowed to read contact lists</td></tr>");
		}

		$(document).on('click', '.edit_contact_list_btn', function(){
			$('#edit_contact_list_modal').modal('show');
			$('#contact_list_id, #contact_list_title, #contact_list_description').val('');
			$('#edit_contact_list_form_msg').removeClass('alert alert-danger alert-success').html('');

			var id = $(this).data('id');

			if(id != '' && id != 0) {
				if(user_info.user_role.contact_lists.edit == 1) {
					$.ajax({
						url: api_url,
						type: 'POST',
						data: JSON.stringify({ action:'single_contact_list', contact_list_id:id, user_id:user_info.id }),
						success: function(result) {
							if(result.status_code == '001') {
								$('#contact_list_id').val(result.display_records.id);
								$('#contact_list_title').val(result.display_records.contact_list_title);
								$('#contact_list_description').val(result.display_records.contact_list_description);
							}
						}
					});
				} else {
					alert("You're not allowed to edit contact lists");
				}
			}
			// }

		});

		$('#edit_contact_list_form').on('submit', function(e){
			e.preventDefault();

			var contact_list_id = $('#contact_list_id').val();
			var contact_list_title = $('#contact_list_title').val();
			var contact_list_description = $('#contact_list_description').val();
			var bool = 0;

			if(contact_list_title == '') {
				$('#contact_list_title').removeClass('is-valid').addClass('is-invalid');
				$('#contact_list_title_msg').removeClass('valid-feedback').addClass('invalid-feedback').text('Please Enter Contact List Title');
				bool = 1;
			} else {
				$('#contact_list_title').removeClass('is-invalid is-valid');
				$('#contact_list_title_msg').removeClass('valid-feedback invalid-feedback').text('');
				bool = 0;
			}

			if(contact_list_id == '') {
				$('#edit_contact_list_form_msg').removeClass('alert-success').addClass('alert alert-danger').text('Something went wrong please reopen the edit form...');
				bool = 1;
			} else {
				bool = 0;
			}

			if(bool == 0) {

				if(user_info.user_role.contact_lists.edit == 1) {

					$.ajax({
						url: api_url,
						type: 'POST',
						beforeSend: function() {
							$('#edit_contact_list_form_msg').removeClass('alert-danger').addClass('alert alert-success').text('Please Wait...');
							$('#edit_contact_list_form_btn').addClass('disabled');
						},
						data: JSON.stringify({ action:'edit_contact_list', user_id:user_info.id, contact_list_id:contact_list_id, contact_list_title:contact_list_title, contact_list_description:contact_list_description }),
						success: function(result) {
							if(result.status_code == '001') {
								$('#edit_contact_list_form_msg').removeClass('alert-danger').addClass('alert alert-success').text('Contact List Successfully Updated Please wait...');
								display_contact_lists();
								setTimeout(function(){
									$('#edit_contact_list_modal').modal('hide');
								}, 1000);
							} else if(result.status_code == '002') {
								$('#edit_contact_list_form_msg').removeClass('alert-success').addClass('alert alert-danger').text('Please Try Again');
							} else if(result.status_code == '003') {
								$('#edit_contact_list_form_msg').removeClass('alert-success').addClass('alert alert-danger').text('Please Fill Required Fields');
							}
						}
					});

				} else {
					alert("You're not allowed to edit contact lists");
				}

			}

		});

		$(document).on('click', '.delete_contact_lists_btn', function(){
			var id = $(this).data('id');

			if(user_info.user_role.contact_lists.delete == 1) {
				if(id != '' && id != 0) {

					if(confirm('Are you sure to delete this?')) {

						$.ajax({
							url: api_url,
							type: 'POST',
							data: JSON.stringify({ action:'delete_contact_list', user_id:user_info.id, id:id }), 
							success: function(result) {
								if(result.status_code == '001') {
									$('#contacts_msg').removeClass('alert-danger').addClass('alert alert-success').text('Contact List Delete Successfully');
									display_contact_lists();
								} else if(result.status_code == '002') {
									$('#contacts_msg').removeClass('alert-success').addClass('alert alert-danger').text('Please Try Again');
								}
								setTimeout(function(){
									$('#contacts_msg').removeClass('alert alert-danger alert-success').text('');
								}, 1000);
							}
						});

					}

				}
			} else {
				alert("You're not allowed to delete contact lists");
			}
		});

	</script>
</body>
</html>
