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
							<h1 class="m-0 d-inline">Setting</h1>
						</div><!-- /.col -->
						<div class="col-sm-6">
							<ol class="breadcrumb float-sm-right">
								<li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
								<li class="breadcrumb-item active">Setting</li>
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
										<h5 class="card-heading d-inline">Account Setting</h5>
									</div>
									<div class="col-md-6">
									</div>
								</div>
								<div class="card-body">
									<form class="form" method="post" id="new_account_form">

										<div id="new_account_form_msg"></div>
										
										<div class="mb-3">
											<label>First Name:</label>
											<input type="text" class="form-control form-control-border" placeholder="Enter First Name" name="first_name" id="first_name">
											<div id="first_name_msg"></div>
										</div>

										<div class="mb-3">
											<label>Last Name:</label>
											<input type="text" class="form-control form-control-border" placeholder="Enter Last Name" name="last_name" id="last_name">
											<div id="last_name_msg"></div>
										</div>
										
										<div class="mb-3">
											<label>Phone Number:</label>
											<input type="tel" class="form-control form-control-border" placeholder="Enter Phone Number" name="phone_number" id="phone_number">
											<div id="phone_number_msg"></div>
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
		
	</script>
</body>
</html>
