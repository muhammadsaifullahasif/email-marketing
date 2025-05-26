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
							<h1 class="m-0 d-inline">Templates</h1>
							<a href="template-new.php" id="add_template_btn" class="btn btn-outline-dark btn-sm mb-3 ml-2">Add New</a>
						</div><!-- /.col -->
						<div class="col-sm-6">
							<ol class="breadcrumb float-sm-right">
								<li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
								<li class="breadcrumb-item active">Templates</li>
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
										<h5 class="card-heading d-inline">Templates</h5>
									</div>
									<div class="col-md-6">
									</div>
								</div>
								<div class="card-body dropdown" style="height: 400px; max-height: 100%; overflow-y: auto;">

									<div id="new_template_msg"></div>

									<div class="table-responsive">
										<table class="table table-striped table-hover table-sm">
											<thead>
												<tr>
													<th><input type="checkbox" name="group_input_main" class="group_input_main group_input"></th>
													<th>Title</th>
													<th>Category</th>
													<th>Status</th>
													<th>Action</th>
												</tr>
											</thead>
											<tbody id="display_templates">
											</tbody>
										</table>
									</div>

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

		$(document).on('change', '.group_input', function(e) {
			$(this).attr('checked', true);
			if($('.group_input_main').is(':checked')) {
				$('.group_input').attr('checked', 'checked');
			} else {
				$('.group_input').removeAttr('checked');
			}
			$(this).trigger('reset');
		});

		if(user_info.user_role.templates.add == 0) {
			$('#add_template_btn').hide();
		}

		$(document).on('click', '.delete_template_btn', function(){
			var template_id = $(this).data('id');
			if(user_info.user_role.templates.delete == 1) {
				if(template_id != '' && template_id != 0) {
					if(confirm('Are you sure to delete template?')) {
						$.ajax({
							url: api_url,
							type: 'POST',
							data: JSON.stringify({ 'action' : 'delete_template', 'template_id' : template_id, user_id: user_info.id }),
							success: function(result) {
								if(result.status_code == '001') {
									$('#new_template_msg').removeClass('alert-danger').addClass('alert alert-success').text('Template Successfully Deleted');
									display_templates();
								} else {
									$('#new_template_msg').removeClass('alert-success').addClass('alert alert-danger').html("Please Try Again");
								}
								setTimeout(function(){
									$('#new_template_msg').removeClass('alert alert-danger alert-success').html('');
								}, 1000);
							}
						});
					}
				}
			}
		});

		function display_templates() {
			templates = templates_function(user_info);
			$('#display_templates').html('');
			if(templates.status_code == '001') {
				$.each(templates.display_records, function(key, value) {
					var active_class, active_title;
					if(value.active_status == 1) {
						active_class = 'badge-success';
						active_title = 'Active';
					} else if(value.active_status == 0) {
						active_class = 'badge-danger';
						active_title = 'Inactive';
					}

					var edit_button, delete_button;
					if(value.user_id == null) {
						if(user_info.user_role.templates.edit == 1) {
							edit_button = '<button data-id="'+ value.id +'" class="btn btn-primary duplicate_template_btn btn-sm">Duplicate</button>';
						}
						delete_button = '';
					} else {
						if(user_info.user_role.templates.edit == 1) {
							edit_button = '<a href="editor.php?id='+ value.id +'" class="btn btn-primary btn-sm">Edit</a>';
						}
						if(user_info.user_role.templates.delete == 1) {
							delete_button = '<button data-id="'+ value.id +'" class="btn btn-danger delete_template_btn btn-sm">Delete</button>';
						}
					}

					$('#display_templates').append(
						'<tr>' + 
							'<td><input data-id="'+ value.id +'" type="checkbox" class="group_input" name="group_input"></td>' + 
							'<td>'+ value.template_name +'</td>' + 
							'<td>'+ value.category_name +'</td>' + 
							'<td><span class="badge '+ active_class +'">'+ active_title +'</span></td>' + 
							'<td>' + 
								'<div class="btn-group">' + 
									edit_button + delete_button + 
								'</div>' + 
							'</td>' + 
						'</tr>'
					);
				});
			} else {
				$('#display_templates').html("<tr><td colspan='5' class='text-center'>No Record Found</td></tr>");
			}
		}

		if(user_info.user_role.templates.read == 1) {
			display_templates();
		} else {
			$('#display_templates').html("<tr><td colspan='5' class='text-center'>You're not allowed to read templates</td></tr>");
		}

		$(document).on('click', '.duplicate_template_btn', function(){
			var id = $(this).data('id');
			if(user_info.user_role.templates.add == 1) {
				if(id != '' && id != 0) {
					if(confirm('Are you sure to duplicate template?')) {
						$.ajax({
							url: api_url,
							type: 'POST', 
							data: JSON.stringify({ action: 'duplicate_template', template_id: id, user_id: user_info.id }), 
							success: function(result) {
								if(result.status_code == '001') {
									$('#new_template_msg').removeClass('alert-danger').addClass('alert alert-success').text('Template Successfully Duplicated');
								} else if(result.status_code == '002') {
									if(result.status_sub_code == '2001') {
										$('#new_template_msg').removeClass('alert-success').addClass('alert alert-danger').html("<a href='"+main_url+"'>Upgrade to Premium<i class='fas fa-crown ml-1'></i> Version</a>");
									} else {
										$('#new_template_msg').removeClass('alert-success').addClass('alert alert-danger').html("Please Try Again");
									}
								}
								display_templates();

								setTimeout(function(){
									$('#new_template_msg').removeClass('alert alert-danger alert-success').html('');
								}, 1000);
							}
						});
					}
				}
			}
		});

	</script>
</body>
</html>
