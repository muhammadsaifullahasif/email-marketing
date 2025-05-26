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
								<li class="breadcrumb-item"><a href="templates.php">Templates</a></li>
								<li class="breadcrumb-item active">Template New</li>
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
										<h5 class="card-heading d-inline">Template New</h5>
									</div>
									<div class="col-md-6">
									</div>
								</div>
								<div class="card-body dropdown" style="height: 400px; max-height: 100%; overflow-y: auto;">

									<form class="form" id="new_template_form" method="post">

										<div id="new_template_form_msg"></div>
										
										<div class="mb-3">
											<label>Name:</label>
											<input type="text" class="form-control form-control-border" placeholder="Enter Name" id="template_name" name="template_name">
											<div id="template_name_msg"></div>
										</div>

										<div class="mb-3">
											<label>Category:</label>
											<select class="form-control form-control-border" id="template_category" name="template_category">
												<option value="">Select Category</option>
											</select>
										</div>

										<button class="btn btn-dark" id="new_template_form_btn" name="new_template_form_btn" type="submit">Submit</button>

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

		if(user_info.user_role.templates.add == 0) {
			$('#add_template_btn').hide();
			window.top.location = dashboard_url +'templates.php';
		}
		
		$('#template_name').on('focus', function(){
			$('#template_name').removeClass('is-invalid is-valid');
			$('#template_name_msg').removeClass('invalid-feedback valid-feedback').text('');
		});

		$('#new_template_form').on('submit', function(e){
			e.preventDefault();
			var template_name = $('#template_name').val();
			var template_category = $('#template_category').val();
			var bool = 0;

			if(template_name == '') {
				$('#template_name').removeClass('is-valid').addClass('is-invalid');
				$('#template_name_msg').removeClass('valid-feedback').addClass('invalid-feedback').text('Template name required!');
				bool = 1;
			} else {
				$('#template_name').removeClass('is-invalid is-valid');
				$('#template_name_msg').removeClass('invalid-feedback valid-feedback').text('');
				bool = 0;
			}

			if(user_info.user_role.templates.add == 1) {
				if(bool == 0) {
					if(user_info.user_role.templates.add == 1) {
						$.ajax({
							url: api_url,
							type: 'POST',
							beforeSend: function() {
								$('#new_template_form_msg').removeClass('alert-danger').addClass('alert alert-success').text('Please Wait...');
								$('#new_template_form_btn').addClass('disabled');
							},
							data: JSON.stringify({ 'action' : 'new_template', 'user_id' : user_info.id, 'template_name' : template_name, 'template_category' : template_category }),
							success: function(result) {
								if(result.status_code == '001') {
									$('#new_template_form_msg').removeClass('alert-danger').addClass('alert alert-success').text('Template Successfully Added, Please Wait...');
									setTimeout(function(){
										window.location.href = dashboard_url+'templates.php';
									}, 1000);
								} else if(result.status_code == '002') {
									if(result.status_sub_code == '2001') {
										$('#new_template_form_msg').removeClass('alert-success').addClass('alert alert-danger').html("<a href='"+main_url+"'>Upgrade to Premium<i class='fas fa-crown ml-1'></i> Version</a>");
									} else {
										$('#new_template_form_msg').removeClass('alert-success').addClass('alert alert-danger').text('Please Try Again');
									}
								} else if(result.status_code == '003') {
									$('#new_template_form_msg').removeClass('alert-success').addClass('alert alert-danger').text('Please Fill Required Fields');
								}
								$('#new_template_form_btn').removeClass('disabled');
							}
						});
					} else {
						alert("You're not allowed to add templates");
					}
				}
			}
		});

		function display_template_category_select() {
			$.ajax({
				url: api_url, 
				type: 'POST', 
				data: JSON.stringify({ 'action' : 'get_template_categories', 'user_id' : user_info.id }), 
				success: function(result) {
					if(result.status_code == '001') {
						$('#template_category').html("<option value=''>Select Category</option>");
						var i = 1;
						$.each(result.display_records, function(key, value) {
							if(value.active_status != 1)
								return;

							if(i == 1) {
								template_category_selected = 'selected';
							} else {
								template_category_selected = '';
							}
							$('#template_category').append(
								'<option '+template_category_selected+' value="'+value.id+'">'+value.template_category_name+'</option>'
							);
							i++;
						});
					}
				}
			});
		}
		display_template_category_select();

	</script>
</body>
</html>
