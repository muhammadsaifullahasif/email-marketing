<?php

if(isset($_GET['id'])) {
	$id = strip_tags($_GET['id']);
}

?>
<!doctype html>
<html lang="en">
<head>
	<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

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
	</style>
</head>
<body>
	
	<div class="container">
		
		<header class="mb-3"></header>

		<main id="main">
			<h3 class="border-bottom py-2">Configure</h3>
			<div class="row">
				<div class="col-md-8">
					<p>Configure your desired options and continue to checkout.</p>
					<div class="product-info">
						<p class="product-title" id="product-title"></p>
						<p class="product-features" id="product-features"></p>
					</div>

					<div class="field-container">
						<input type="hidden" id="monthly_plan">
						<input type="hidden" id="half_year_plan">
						<input type="hidden" id="yearly_plan">
						<input type="hidden" id="bi_year_plan">
						<input type="hidden" id="tri_year_plan">
						<div class="mb-3">
							<label>Choose Billing Cycle:</label>
							<select class="form-control" id="billing_cycle"></select>
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
							<button id="checkout-btn" class="btn btn-dark btn-lg" type="button">Checkout</button>
						</div>
					</div>
				</div>
			</div>

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

			var monthly_selected = '';
			var half_year_selected = '';
			var yearly_selected = '';
			var bi_year_selected = '';
			var tri_year_selected = '';

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

			$('#checkout-btn').on('click', function(){
				var package_id = user_info.user_cart.subscription_id;
				var billing_cycle = $('#billing_cycle').val();

				$.ajax({
					url: api_url,
					type: 'POST',
					data: JSON.stringify({ 'action' : 'update_cart', 'user_id' : user_info.id, 'subscription_id' : package_id, 'billing_cycle' : billing_cycle }),
					success: function(result) {
						if(result.status_code == '001') {
							window.location.href = '/email-marketing/checkout.php';
						} else {
							alert('Please Try Again');
						}
					}
				});

				// window.location.href ='/email-marketing/checkout.php';
			});

			console.log(user_info);
			var subscription_id = user_info.user_cart.subscription_id;
			// var billing_cycle = user_info.user_cart.billing_cycle;
			if(user_info.user_cart.billing_cycle == 'monthly') {
				monthly_selected += 'selected';
			} else if(user_info.user_cart.billing_cycle == 'half_year') {
				half_year_selected += 'selected';
			} else if(user_info.user_cart.billing_cycle == 'yearly') {
				yearly_selected += 'selected';
			} else if(user_info.user_cart.billing_cycle == 'bi_year') {
				bi_year_selected += 'selected';
			} else if(user_info.user_cart.billing_cycle == 'tri_year') {
				tri_year_selected += 'selected';
			}

			<?php

			} else {
			?>
			var subscription_id = <?php echo $id; ?>;
			var billing_cycle = '';
			yearly_selected += 'selected';

			$('#checkout-btn').on('click', function(){
				var package_id = <?php echo $id; ?>;
				var billing_cycle = $('#billing_cycle').val();
				window.location.href ='/email-marketing/signup.php?id='+package_id+'&billing_cycle='+billing_cycle;
			});

			<?php
			}

			?>

			$.ajax({
				url: api_url,
				type: 'POST',
				data: JSON.stringify({ "action" : "single_subscription", "subscription_id" : subscription_id }),
				success: function(result) {
					console.log(result);
					$('#product-title, .product-name').text(result.name +" Package");
					var features = '';
					if(result.features.email_accounts.limit != false) {
						if(result.features.email_accounts.limit == -1) {
							features += 'Unlimited '+ result.features.email_accounts.title +' - ';
						} else {
							features += result.features.email_accounts.limit +' '+ result.features.email_accounts.title +' - ';
						}
					}

					if(result.features.tracking_email.limit != false) {
						if(result.features.tracking_email.limit == -1) {
							features += 'Unlimited '+ result.features.tracking_email.title +' - ';
						} else {
							features += result.features.tracking_email.limit +' '+ result.features.tracking_email.title +'/Account/Month - ';
						}
					}

					if(result.features.urgent_email.limit != false) {
						if(result.features.urgent_email.limit == -1) {
							features += 'Unlimited '+ result.features.urgent_email.title +' - ';
						} else {
							features += result.features.urgent_email.limit +' '+ result.features.urgent_email.title +'/Account/Month - ';
						}
					}

					if(result.features.staff_accounts.limit != false) {
						if(result.features.staff_accounts.limit == -1) {
							features += 'Unlimited '+ result.features.staff_accounts.title +' - ';
						} else {
							features += result.features.staff_accounts.limit +' '+ result.features.staff_accounts.title +' - ';
						}
					}

					if(result.features.email_templates.limit != false) {
						if(result.features.email_templates.limit == -1) {
							features += 'Unlimited '+ result.features.email_templates.title +' - ';
						} else {
							features += result.features.email_templates.limit +' '+ result.features.email_templates.title +' - ';
						}
					}

					if(result.features.dynamic_email.limit != false) {
						if(result.features.dynamic_email.limit == -1) {
							features += 'Unlimited '+ result.features.dynamic_email.title +' - ';
						} else {
							features += result.features.dynamic_email.limit +' '+ result.features.dynamic_email.title +'/Account/Month - ';
						}
					}

					if(result.features.api_integration.limit != false) {
						features += result.features.api_integration.title +' - ';
					}

					if(result.features.campaigns.limit != false) {
						if(result.features.campaigns.limit == -1) {
							features += 'Unlimited '+ result.features.campaigns.title +' - ';
						} else {
							features += result.features.campaigns.limit +' '+ result.features.campaigns.title;
						}
					}
					$('#product-features').html(features);
					$('#monthly_plan').attr('data-currency', result.price.currency.symbol).attr('data-title', result.price.monthly.title).attr('data-regular-price', result.price.monthly.regular_price).attr('data-sale-price', result.price.monthly.sale_price).attr('data-price', result.price.monthly.price);
					$('#half_year_plan').attr('data-currency', result.price.currency.symbol).attr('data-title', result.price.half_year.title).attr('data-regular-price', result.price.half_year.regular_price).attr('data-sale-price', result.price.half_year.sale_price).attr('data-price', result.price.half_year.price);
					$('#yearly_plan').attr('data-currency', result.price.currency.symbol).attr('data-title', result.price.yearly.title).attr('data-regular-price', result.price.yearly.regular_price).attr('data-sale-price', result.price.yearly.sale_price).attr('data-price', result.price.yearly.price);
					$('#bi_year_plan').attr('data-currency', result.price.currency.symbol).attr('data-title', result.price.bi_year.title).attr('data-regular-price', result.price.bi_year.regular_price).attr('data-sale-price', result.price.bi_year.sale_price).attr('data-price', result.price.bi_year.price);
					$('#tri_year_plan').attr('data-currency', result.price.currency.symbol).attr('data-title', result.price.tri_year.title).attr('data-regular-price', result.price.tri_year.regular_price).attr('data-sale-price', result.price.tri_year.sale_price).attr('data-price', result.price.tri_year.price);
					$('#billing_cycle').append(
						"<option value='monthly'"+monthly_selected+">" + result.price.currency.symbol + result.price.monthly.price +" "+ result.price.currency.slug +" - "+ result.price.monthly.title +"</option>" +
						"<option value='half_year'"+half_year_selected+">" + result.price.currency.symbol + result.price.half_year.price +" "+ result.price.currency.slug +" - "+ result.price.half_year.title +"</option>" +
						"<option value='yearly' "+yearly_selected+">" + result.price.currency.symbol + result.price.yearly.price +" "+ result.price.currency.slug +" - "+ result.price.yearly.title +"</option>" +
						"<option value='bi_year'"+bi_year_selected+">" + result.price.currency.symbol + result.price.bi_year.price +" "+ result.price.currency.slug +" - "+ result.price.bi_year.title +"</option>" +
						"<option value='tri_year'"+tri_year_selected+">" + result.price.currency.symbol + result.price.tri_year.price +" "+ result.price.currency.slug +" - "+ result.price.tri_year.title +"</option>"
					);
					$('.product-price').text(result.price.currency.symbol + result.price.yearly.regular_price);
					if(result.price.yearly.regular_price == result.price.yearly.price) {
						$('.discount').text(result.price.currency.symbol+'0');
					} else {
						$('.discount').text(result.price.currency.symbol+(result.price.yearly.regular_price - result.price.yearly.sale_price));
					}
					$('.total-price').text(result.price.currency.symbol+(result.price.yearly.regular_price - (result.price.yearly.regular_price - result.price.yearly.sale_price)));
					$('.billing_duration').text(result.price.yearly.title);
				}
			});

			$('#billing_cycle').on('change', function(){
				var value = $(this).val();
				var title = $('#'+value+'_plan').data('title');
				var currency = $('#'+value+'_plan').data('currency');
				var regular_price = $('#'+value+'_plan').data('regular-price');
				var sale_price = $('#'+value+'_plan').data('sale-price');
				var price = $('#'+value+'_plan').data('price');
				$('.product-price').text(currency+regular_price);
				$('.billing_duration').text(title);
				if(regular_price == price) {
					$('.discount').text(currency+'0');
					$('.total-price').text(currency+(regular_price));
				} else {
					$('.discount').text(currency+(regular_price - sale_price));
					$('.total-price').text(currency+(regular_price - (regular_price - sale_price)));
				}
			});

		});
	</script>
</body>
</html>
