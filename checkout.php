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
		.product-info {
			margin: 0 0 20px 0;
			padding: 6px 15px;
			font-size: 0.85em;
			background-color: #f8f8f8;
			border-top: 1px solid #efefef;
			border-bottom: 1px solid #efefef;
		}
		.product-title {
			margin: 0;
			font-size: 1.6em;
		}
		.product-features {
			font-size: 0.85em;
		}
		.field-container {
			margin: 0 0 30px 0;
		}
		.order-summary {
			margin: 0 0 20px 0;
			padding: 0;
			background-color: #666;
			border-bottom: 3px solid #666;
			border-radius: 4px;
		}
		.order-summary h2 {
			margin: 0;
			padding: 10px;
			color: #fff;
			text-align: center;
			font-size: 1.4em;
			font-weight: normal;
		}
		.summary-container {
			margin: 0;
			padding: 10px;
			min-height: 100px;
			border-radius: 3px;
			background-color: #f8f8f8;
			font-size: 0.8em;
		}
		.product-name, .product-price, .total-price {
			font-weight: bold;
			font-size: 1.2em;
		}
		.clearfix::after {
			clear: both;
		}
		.summary-total {
			margin: 5px 0;
			padding: 5px 0;
			border-top: 1px solid #ccc;
			border-bottom: 1px solid #ccc;
		}
		.total-due-today span {
			display: block;
			text-align: right;
		}
		.amt {
			font-size: 2.3em;
		}
		.sub-heading {
			height: 0;
			border-top: 1px solid #ddd;
			text-align: center;
			margin-top: 20px;
			margin-bottom: 30px;
		}
		.sub-heading span {
			display: inline-block;
			position: relative;
			padding: 0 17px;
			top: -13px;
			font-size: 16px;
			color: #058;
			background-color: #fff;
		}
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
	
	<div class="container">
		
		<header class="mb-3"></header>

		<main id="main">
			<h3 class="border-bottom py-2">Checkout</h3>
			<form class="row" id="checkout_form" method="post">
				<div class="col-md-12">
					<div id="checkout_form_msg"></div>
				</div>
				<div class="col-md-8">
					<div class="already-registered clearfix">
						<div class="float-right">
							<input type="hidden" value="signup" id="account_action" name="account_action">
							<!-- <button class="btn btn-info" id="login_btn" type="button">Already Registered?</button> -->
							<!-- <button class="btn btn-info" id="signup_btn" style="display: none;" type="button">Create a New Account?</button> -->
						</div>
						<p>Please enter your personal details and billing information to checkout.</p>
					</div>

					<!-- <div id="containerExistingUserSignin" style="display: none;">
						<div class="sub-heading">
							<span>Existing Customer Login</span>
						</div>

						<div class="row">
							<div class="col-md-6">
								<div class="mb-3 prepend-icon">
									<label class="field-icon"><i class="fas fa-user"></i></label>
									<input type="text" class="field" placeholder="Username" name="user_login" id="login_user">
								</div>
							</div>
							<div class="col-md-6">
								<div class="mb-3 prepend-icon">
									<label class="field-icon"><i class="fas fa-lock"></i></label>
									<input type="password" class="field" placeholder="Password" name="user_pass" id="login_pass">
								</div>
							</div>
						</div>
					</div> -->

					<!-- <div id="containerNewUserSignup">
						<div class="sub-heading">
							<span>Billing Information</span>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="mb-3 prepend-icon">
									<label class="field-icon"><i class="fas fa-user"></i></label>
									<input type="text" class="field form-control" placeholder="First Name" name="first_name" id="first_name">
									<div id="first_name_msg"></div>
								</div>
							</div>
							<div class="col-md-6">
								<div class="mb-3 prepend-icon">
									<label class="field-icon"><i class="fas fa-user"></i></label>
									<input type="text" class="field form-control" placeholder="Last Name" name="last_name" id="last_name">
									<div id="last_name_msg"></div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="mb-3 prepend-icon">
									<label class="field-icon"><i class="fas fa-envelope"></i></label>
									<input type="text" class="field form-control" placeholder="Email Address" name="user_email" id="user_email">
									<div id="user_email_msg"></div>
								</div>
							</div>
							<div class="col-md-6">
								<div class="mb-3 prepend-icon">
									<label class="field-icon"><i class="fas fa-phone-alt"></i></label>
									<input type="text" class="field form-control" placeholder="Phone Number" name="user_phone" id="user_phone">
									<div id="user_phone_msg"></div>
								</div>
							</div>
						</div>
					</div> -->
					<div id="containerPayment">
						<div class="sub-heading">
							<span>Payment Details</span>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="prepend-icon mb-3">
									<label class="field-icon"><i class="fas fa-user"></i></label>
									<input type="text" class="form-control field" placeholder="Card holder name" name="card_holder_name" id="card_holder_name">
									<div id="card_holder_name_msg"></div>
								</div>
							</div>
							<div class="col-md-6">
								<div class="prepend-icon mb-3">
									<label class="field-icon"><i class="fas fa-credit-card"></i></label>
									<input type="text" class="formc-control field" placeholder="Card Number" name="card_number" id="card_number">
									<div id="card_number_msg"></div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-2">
								<div class="prepend-icon mb-3">
									<label class="field-icon"><i class="fas fa-calendar-alt"></i></label>
									<input type="text" class="form-control field" placeholder="MM" name="card_expiry_month" id="card_expiry_month">
									<div id="card_expiry_month_msg"></div>
								</div>
							</div>
							<div class="col-sm-2">
								<div class="prepend-icon mb-3">
									<label class="field-icon"><i class="fas fa-calendar-alt"></i></label>
									<input type="text" class="form-control field" placeholder="YY" name="card_expiry_year" id="card_expiry_year">
									<div id="card_expiry_year_msg"></div>
								</div>
							</div>
							<div class="col-md-8">
								<div class="prepend-icon mb-3">
									<label class="field-icon"><i class="fas fa-lock"></i></label>
									<input type="text" class="form-control field" placeholder="CSV" name="card_csv" id="card_csv">
									<div id="card_csv_msg"></div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-4">
					<div id="order-summary" style="margin-top: 8px;">
						<div class="order-summary">
							<h2>Order Summary</h2>
							<div id="product-total" class="summary-container">
								<div class="clearfix">
									<span class="product-name float-left"></span>
									<span class="product-price float-right"></span>
								</div>
								<div class="summary-total">
									<div class="clearfix">
										<span class="services-charges-name float-left">Services Charges</span>
										<span class="services-charges float-right">$0</span>
									</div>
									<div class="clearfix">
										<span class="billing_duration float-left"></span>
										<span class="product-price float-right"></span>
									</div>
									<div class="clearfix">
										<span class="discount-name float-left">Discount</span>
										<span class="discount float-right"></span>
									</div>
								</div>
								<div class="total-due-today">
									<span class="amt total-price"></span>
									<span>Total Due Today</span>
								</div>
							</div>
						</div>
						<div class="text-center">
							<button id="checkout_form_btn" name="submit" class="btn btn-dark btn-lg" type="submit">Complete Order</button>
						</div>
					</div>
				</div>
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

			<?php

			if(isset($_COOKIE['email_marketing_session_key'])) {
			?>
				$.ajax({
					url: api_url,
					type: 'POST',
					async: false,
					data: JSON.stringify({ 'action' : 'user_info', 'session_key' : '<?php echo $_COOKIE['email_marketing_session_key']; ?>' }),
					success: function(result) {
						user_info = result;
						// tmp = result;
					}
				});
			<?php
			} else {
			?>
				window.location.href = home_url;
			<?php
			}

			?>

			$('#login_btn').on('click', function(){
				$('#containerExistingUserSignin').show();
				$('#containerNewUserSignup').hide();
				$('#login_btn').hide();
				$('#signup_btn').show();
				$('#account_action').val('login');
			});
			$('#signup_btn').on('click', function(){
				$('#containerExistingUserSignin').hide();
				$('#containerNewUserSignup').show();
				$('#login_btn').show();
				$('#signup_btn').hide();
				$('#account_action').val('signup');
			});
			$.ajax({
				url: api_url,
				type: 'POST',
				data: JSON.stringify({ "action" : "single_subscription", "subscription_id" : user_info.user_cart.subscription_id }),
				success: function(result) {
					// console.log(result);
					$('#product-title, .product-name').text(result.name +" Package");
					// $('.product-price').text(result.price.currency.symbol + result.price.yearly.regular_price);
					if(user_info.user_cart.billing_cycle == 'monthly') {
						$('.product-price').text(result.price.currency.symbol + result.price.monthly.regular_price);
						if(result.price.monthly.regular_price == result.price.monthly.price) {
							$('.discount').text(result.price.currency.symbol+'0');
							$('.total-price').text(result.price.currency.symbol+(result.price.monthly.price));
						} else {
							$('.discount').text(result.price.currency.symbol+(result.price.monthly.regular_price - result.price.monthly.sale_price));
							$('.total-price').text(result.price.currency.symbol+(result.price.monthly.regular_price - (result.price.monthly.regular_price - result.price.monthly.sale_price)));
						}
						$('.billing_duration').text(result.price.monthly.title);
					} else if(user_info.user_cart.billing_cycle == 'half_year') {
						$('.product-price').text(result.price.currency.symbol + result.price.half_year.regular_price);
						if(result.price.half_year.regular_price == result.price.half_year.price) {
							$('.discount').text(result.price.currency.symbol+'0');
							$('.total-price').text(result.price.currency.symbol+(result.price.half_year.price));
						} else {
							$('.discount').text(result.price.currency.symbol+(result.price.half_year.regular_price - result.price.half_year.sale_price));
							$('.total-price').text(result.price.currency.symbol+(result.price.half_year.regular_price - (result.price.half_year.regular_price - result.price.half_year.sale_price)));
						}
						$('.billing_duration').text(result.price.half_year.title);
					} else if(user_info.user_cart.billing_cycle == 'yearly') {
						$('.product-price').text(result.price.currency.symbol + result.price.yearly.regular_price);
						if(result.price.yearly.regular_price == result.price.yearly.price) {
							$('.discount').text(result.price.currency.symbol+'0');
							$('.total-price').text(result.price.currency.symbol+(result.price.yearly.price));
						} else {
							$('.discount').text(result.price.currency.symbol+(result.price.yearly.regular_price - result.price.yearly.sale_price));
							$('.total-price').text(result.price.currency.symbol+(result.price.yearly.regular_price - (result.price.yearly.regular_price - result.price.yearly.sale_price)));
						}
						$('.billing_duration').text(result.price.yearly.title);
					} else if(user_info.user_cart.billing_cycle == 'bi_year') {
						$('.product-price').text(result.price.currency.symbol + result.price.bi_year.regular_price);
						if(result.price.bi_year.regular_price == result.price.bi_year.price) {
							$('.discount').text(result.price.currency.symbol+'0');
							$('.total-price').text(result.price.currency.symbol+(result.price.bi_year.price));
						} else {
							$('.discount').text(result.price.currency.symbol+(result.price.bi_year.regular_price - result.price.bi_year.sale_price));
							$('.total-price').text(result.price.currency.symbol+(result.price.bi_year.regular_price - (result.price.bi_year.regular_price - result.price.bi_year.sale_price)));
						}
						$('.billing_duration').text(result.price.bi_year.title);
					} else if(user_info.user_cart.billing_cycle == 'tri_year') {
						$('.product-price').text(result.price.currency.symbol + result.price.tri_year.regular_price);
						if(result.price.tri_year.regular_price == result.price.tri_year.price) {
							$('.discount').text(result.price.currency.symbol+'0');
							$('.total-price').text(result.price.currency.symbol+(result.price.tri_year.price));
						} else {
							$('.discount').text(result.price.currency.symbol+(result.price.tri_year.regular_price - result.price.tri_year.sale_price));
							$('.total-price').text(result.price.currency.symbol+(result.price.tri_year.regular_price - (result.price.tri_year.regular_price - result.price.tri_year.sale_price)));
						}
						$('.billing_duration').text(result.price.tri_year.title);
					}
				}
			});

			$('#card_holder_name').on('focus', function(){
				$('#card_holder_name').removeClass('is-invalid is-valid');
				$('#card_holder_name_msg').removeClass('invalid-feedback valid-feedback').text('');
			});

			$('#card_number').on('focus', function(){
				$('#card_number').removeClass('is-invalid is-valid');
				$('#card_number_msg').removeClass('invalid-feedback valid-feedback').text('');
			});

			$('#card_expiry_month').on('focus', function(){
				$('#card_expiry_month').removeClass('is-invalid is-valid');
				$('#card_expiry_month_msg').removeClass('invalid-feedback valid-feedback').text('');
			});

			$('#card_expiry_year').on('focus', function(){
				$('#card_expiry_year').removeClass('is-invalid is-valid');
				$('#card_expiry_year_msg').removeClass('invalid-feedback valid-feedback').text('');
			});

			$('#card_csv').on('focus', function(){
				$('#card_csv').removeClass('is-invalid is-valid');
				$('#card_csv_msg').removeClass('invalid-feedback valid-feedback').text('');
			});

			$('#checkout_form').on('submit', function(e){
				e.preventDefault();
				var card_holder_name = $('#card_holder_name').val();
				var card_number = $('#card_number').val();
				var card_expiry_month = $('#card_expiry_month').val();
				var card_expiry_year = $('#card_expiry_year').val();
				var card_csv = $('#card_csv').val();
				var bool = 0;

				if(card_holder_name == '') {
					$('#card_holder_name').removeClass('is-valid').addClass('is-invalid');
					// $('#card_holder_name_msg').removeClass('valid-feedback').addClass('invalid-feedback').text('Card Holder Name Required!');
					bool = 1;
				} else {
					$('#card_holder_name').removeClass('is-invalid is-valid');
					// $('#card_holder_name_msg').removeClass('valid-feedback invalid-feedback').text('');
					bool = 0;
				}

				if(card_number == '') {
					$('#card_number').removeClass('is-valid').addClass('is-invalid');
					// $('#card_number_msg').removeClass('valid-feedback').addClass('invalid-feedback').text('Card Number Required!');
					bool = 1;
				} else {
					$('#card_number').removeClass('is-invalid is-valid');
					// $('#card_number_msg').removeClass('invalid-feedback valid-feedback').text('');
					bool = 0;
				}

				if(card_expiry_month == '') {
					$('#card_expiry_month').removeClass('is-valid').addClass('is-invalid');
					// $('#card_expiry_month_msg').removeClass('valid-feedback').addClass('invalid-feedback').text('')
					bool = 1;
				} else {
					$('#card_expiry_month').removeClass('is-invalid is-valid');
					bool = 0;
				}

				if(card_expiry_year == '') {
					$('#card_expiry_year').removeClass('is-valid').addClass('is-invalid');
					bool = 1;
				} else {
					$('#card_expiry_year').removeClass('is-invalid is-valid');
					bool = 0;
				}

				if(card_csv == '') {
					$('#card_csv').removeClass('is-valid').addClass('is-invalid');
					bool = 1;
				} else {
					$('#card_csv').removeClass('is-invalid is-valid');
					bool = 0;
				}

				if(bool == 0) {
					$.ajax({
						url: api_url,
						type: 'POST',
						data: JSON.stringify({ 'action' : 'checkout', 'user_id' : user_info.id, 'subscription_id' : user_info.user_cart.subscription_id, 'billing_cycle' : user_info.user_cart.billing_cycle, 'card_holder_name' : card_holder_name, 'card_number' : card_number, 'card_expiry_month' : card_expiry_month, 'card_expiry_year' : card_expiry_year, 'card_csv' : card_csv }),
						success: function(result) {
							if(result.status_code == '001') {
								$('#checkout_form_msg').removeClass('alert-danger').addClass('alert alert-success').text('Please wait...');
								setTimeout(function(){
									window.location.href = dashboard_url;
								}, 1000);
							} else if(result.status_code == '002') {
								$('#checkout_form_msg').removeClass('alert-success').addClass('alert alert-danger').text('Please Try Again');
							} else if(result.status_code == '003') {
								$('#checkout_form_msg').removeClass('alert-success').addClass('alert alert-danger').text('Fill Required Fields');
							}
						}
					});
				}

			});

		});
	</script>
</body>
</html>
