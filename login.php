<?php

if(isset($_GET['id']) && isset($_GET['billing_cycle'])) {
	$id = strip_tags($_GET['id']);
	$billing_cycle = strip_tags($_GET['billing_cycle']);
}

?>
<!doctype html>
<html lang="en">
<head>
	<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<script src="https://kit.fontawesome.com/d35f256856.js" crossorigin="anonymous"></script>
	<!-- Bootstrap CSS -->
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">

	<title>Hello, world!</title>
	<style type="text/css">
		.prepend-icon {
			top: 0;
			left: 0;
			display: inline-block;
			vertical-align: top;
			position: relative;
			width: 100%;
			font-weight: normal;
		}
		.prepend-icon .field-icon {
			top: 0;
			z-index: 4;
			width: 42px;
			height: 36px;
			color: inherit;
			line-height: 36px;
			position: absolute;
			text-align: center;
			font-weight: 300;
			font-size: 13px;
		}
		.prepend-icon .field-icon i {
			position: relative;
			font-size: 14px;
			color: #bbb;
		}
		.field {
			padding-left: 36px;
			position: relative;
			vertical-align: top;
			border: 1px solid #ddd;
			display: inline-block;
			color: #626262;
			outline: none;
			background-color: #fff;
			height: 36px;
			width: 100%;
			border-radius: 3px;
			font-size: 13px;
		}
	</style>
</head>
<body>
	
	<div class="container d-flex m-auto w-100" style="height: 100vh;">

		<main id="main" class="d-flex w-50 m-auto p-auto text-center flex-column">
			<div class="clearfix mb-3">
				<h3 class="float-left">Login</h3>
				<a href="signup.php<?php if(isset($_GET['id']) && isset($_GET['billing_cycle'])) { echo "?id=".strip_tags($_GET['id'])."&billing_cycle=".strip_tags($_GET['billing_cycle']); } else if(isset($_GET['id'])) { echo "?id=".strip_tags($_GET['id']); } ?>" class="float-right btn btn-info">Dont have an account?</a>
			</div>
			<form class="form m-auto w-100" id="login_form" method="post">
				<div id="login_form_msg"></div>
				<div class="mb-3 prepend-icon">
					<label class="field-icon"><i class="fas fa-user"></i></label>
					<input type="text" class="form-control field" placeholder="Username / Email" name="user_login" id="user_login">
					<div id="user_login_msg"></div>
				</div>
				<div class="mb-3 prepend-icon">
					<label class="field-icon"><i class="fas fa-lock"></i></label>
					<input type="text" class="form-control field" placeholder="Password" name="user_pass" id="user_pass">
					<div id="user_pass"></div>
				</div>
				<button class="btn btn-dark btn-block" id="login_form_btn" name="login_form_btn" type="submit">Login</button>
			</form>

		</main>

	</div>

	<!-- Optional JavaScript; choose one of the two! -->

	<script type="text/javascript" src="https://code.jquery.com/jquery-3.6.1.min.js"></script>
	<!-- Option 1: jQuery and Bootstrap Bundle (includes Popper) -->
	<!-- <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"></script> -->
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
	<script type="text/javascript" src="js/main.js"></script>

	<script type="text/javascript">
		$(document).ready(function(){

			$('#user_login').on('focus', function(){
				$('#user_login').removeClass('is-valid is-invalid');
				$('#user_login_msg').removeClass('invalid-feedback valid-feedback').text('');
			});

			$('#user_pass').on('focus', function(){
				$('#user_pass').removeClass('is-valid is-invalid');
				$('#user_pass_msg').removeClass('invalid-feedback valid-feedback').text('');
			});

			$('#login_form').on('submit', function(e){
				e.preventDefault();

				var user_login = $('#user_login').val();
				var user_pass = $('#user_pass').val();
				var bool = 0;

				if(user_login == '') {
					$('#user_login').removeClass('is-valid').addClass('is-invalid');
					$('#user_login_msg').removeClass('valid-feedback').addClass('invalid-feedback').text('Username Required!');
					bool = 1;
				} else {
					$('#user_login').removeClass('is-invalid is-valid');
					$('#user_login_msg').removeClass('invalid-feedback valid-feedback').text('');
				}

				if(user_pass == '') {
					$('#user_pass').removeClass('is-valid').addClass('is-invalid');
					$('#user_pass_msg').removeClass('valid-feedback').addClass('invalid-feedback').text('Password Required!');
					bool = 1;
				} else {
					$('#user_pass').removeClass('is-invalid is-valid');
					$('#user_pass_msg').removeClass('invalid-feedback valid-feedback').text('');
				}

				if(bool == 0) {

					$.ajax({
						url: api_url,
						type: 'POST',
						data: JSON.stringify({ "action":"user_login", "user_login":user_login, "user_pass":user_pass }),
						success: function(result) {
							if(result.status == 'success') {
								$('#login_form_msg').removeClass('alert-danger').addClass('alert alert-success').text(result.message);
								createCookie('email_marketing_session_key', result.session.session_key, result.session.session_expiry);

								setTimeout(function(){
									window.top.location = dashboard_url;
								}, 1000);
							} else if(result.status == 'error') {
								$('#login_form_msg').removeClass('alert-success').addClass('alert alert-danger').text(result.message);
							}
						}
					});

				}
			});

		});
	</script>
</body>
</html>
