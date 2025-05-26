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
							<h1 class="m-0 d-inline">Campaigns</h1>
							<a href="campaign-new.php" id="add_campaign_btn" class="btn btn-outline-dark btn-sm mb-3 ml-2">Add New</a>
						</div><!-- /.col -->
						<div class="col-sm-6">
							<ol class="breadcrumb float-sm-right">
								<li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
								<li class="breadcrumb-item active">Campaigns</li>
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
										<h5 class="card-heading d-inline">Campaigns</h5>
									</div>
									<div class="col-md-6">
									</div>
								</div>
								<div class="card-body dropdown" style="height: 400px; max-height: 100%; overflow-y: auto;">

									<div id="campaigns_msg"></div>

									<div class="table-responsive">
										<table class="table table-striped table-hover table-sm">
											<thead>
												<tr>
													<th><input type="checkbox" name="group_input_main" class="group_input_main group_input"></th>
													<th>Title</th>
													<th>Subect</th>
													<th>Completed</th>
													<th>Status</th>
													<th>Action</th>
												</tr>
											</thead>
											<tbody id="display_campaigns">
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

		if(user_info.user_role.campaigns.add == 0) {
			$('#add_campaign_btn').hide();
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

		function display_campaigns() {
			display_campaigns = display_campaigns_function(user_info, 'schedule_campaign');
			$('#display_campaigns').html('');
			if(display_campaigns.status_code == '001') {
				$.each(display_campaigns.display_records, function(key, value) {
					var active_class, active_title, is_completed_class, is_completed_title;
					if(value.active_status == 1) {
						active_class = 'badge-success';
						active_title = 'Active';
					} else if(value.active_status == 0) {
						active_class = 'badge-danger';
						active_title = 'Inactive';
					} else {
						active_class = 'badge-warning';
						active_title = value.active_status;
					}

					if(value.is_completed == 1) {
						is_completed_class = 'badge-success';
						is_completed_title = 'Completed';
					} else {
						is_completed_class = 'badge-warning';
						is_completed_title = 'Pending';
					}
					var edit_btn = '', delete_btn = '';

					if(user_info.user_role.campaigns.edit == 1) {
						edit_btn += '<button data-id="'+ value.id +'" class="btn btn-primary btn-sm edit_campaign_btn">Edit</button>';
					}

					if(user_info.user_role.campaigns.delete == 1) {
						delete_btn += '<button data-id="'+ value.id +'" class="btn btn-danger delete_campaign_btn btn-sm">Delete</button>';
					}

					$('#display_campaigns').append(
						'<tr>' + 
							'<td><input data-id="'+ value.id +'" type="checkbox" class="group_input" name="group_input"></td>' + 
							'<td>'+ value.campaign_title +'</td>' + 
							'<td>'+ value.subject +'</td>' + 
							'<td><span class="badge '+ is_completed_class +'">'+ is_completed_title +'</span></td>' + 
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
				$('#display_campaigns').html("<tr><td colspan='6' class='text-center'>No Record Found</td></tr>");
			}
		}

		if(user_info.user_role.campaigns.read == 1) {
			display_campaigns();
		} else {
			$('#display_campaigns').html("<tr><td colspan='6' class='text-center'>You're not allowed to read campaigns</td></tr>");
		}

	</script>
</body>
</html>
