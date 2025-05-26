<?php

// $conn = mysqli_connect('localhost', 'root', '', 'email_marketing');
require_once('config.php');

$session_token = array(
	0 => array(
		'session_key' => '909a0060ca2f5a26e1a16e79a9fc4332', 
		'ip_address' => '::1', 
		'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/109.0.0.0 Safari/537.36', 
		'session_expiry' => '1674990626'
	), 
	1 => array(
		'session_key' => '67636f365c41f32496e54302e7ced98b', 
		'ip_address' => '::1', 
		'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/109.0.0.0 Safari/537.36', 
		'session_expiry' => '1674996701'
	), 
	2 => array(
		'session_key' => '67636f365c41f32496e54302e7ced100', 
		'ip_address' => '::1', 
		'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/109.0.0.0 Safari/537.36', 
		'session_expiry' => '1674996701'
	)
);

/*foreach($session_token as $key => $value) {
    if($value['session_key'] == '909a0060ca2f5a26e1a16e79a9fc4332') {
        unset($session_token[$key]);
    }
}*/

/*$session_token = array_filter($session_token, function($session) {
    return $session['session_key'] == '909a0060ca2f5a26e1a16e79a9fc4332';
});*/

echo '<pre>';
// print_r($session_token);
print_r(array_values($session_token));
echo '</pre>';

// $query = mysqli_query($conn, "INSERT INTO subscription_transactions(subscription_id, user_id, price, duration, payment_method, time_created) VALUES('2', '3', '55', 'yearly', 'credit card', '$time_created')");

/*$user_pass = '$2y$10$nzKup31bSby4bSAegd2PDugs9oGK3apVNTg6w3T9ZMnmgHqipGHam';
$query = mysqli_query($conn, "INSERT INTO users(user_login, user_pass, user_email, display_name, time_created) VALUES('muhammadsaifullahasif', '$user_pass', 'muhammadsaifullahasif@gmail.com', 'Muhammad Saifullah Asif', '$time_created')");
$id = mysqli_insert_id($conn);
$meta_query = mysqli_query($conn, "INSERT INTO user_meta(user_id, meta_key, meta_value) VALUES('$id', 'first_name', 'Muhammad Saifullah'), ('$id', 'last_name', 'Asif'), ('$id', 'user_phone', ''), ('$id', 'session_tokens', ''), ('$id', 'user_cart', '{}'), ('$id', 'user_subscription', ''), ('$id', 'business_name', ''), ('$id', 'website_url', ''), ('$id', 'street_address_1', ''), ('$id', 'street_address_2', ''), ('$id', 'city', ''), ('$id', 'state', ''), ('$id', 'zipcode', ''), ('$id', 'country', '')");*/

/*$free_features = array(
	'email_accounts' => array(
		'title' => 'Email Account',
		'limit' => '1'
	),
	'tracking_email' => array(
		'title' => 'Email Tracking',
		'limit' => false
	),
	'urgent_email' => array(
		'title' => 'Urgent Email',
		'limit' => false
	),
	'staff_accounts' => array(
		'title' => 'Staff Account',
		'limit' => false
	),
	'email_templates' => array(
		'title' => 'Templates',
		'limit' => false
	),
	'data_email' => array(
		'title' => 'Data Email',
		'limit' => false
	),
	'api_integration' => array(
		'title' => 'API Integration',
		'limit' => false
	),
	'campaigns' => array(
		'title' => 'Campaigns/Month',
		'limit' => '1'
	)
);
$free_features_json = json_encode($free_features);
$free_price = array(
	'currency' => array(
		'title' => 'Dollor',
		'slug' => 'USD',
		'symbol' => '$'
	),
	'monthly' => array(
		'title' => '1 Month',
		'regular_price' => '0',
		'sale_price' => '0',
		'price' => '0'
	),
	'half_month' => array(
		'title' => '6 Months',
		'regular_price' => '0',
		'sale_price' => '0',
		'price' => '0'
	),
	'yearly' => array(
		'title' => 'Annually',
		'regular_price' => '0',
		'sale_price' => '0',
		'price' => '0'
	),
	'bi_year' => array(
		'title' => 'Biennially',
		'regular_price' => '0',
		'sale_price' => '0',
		'price' => '0'
	),
	'tri_year' => array(
		'title' => 'Triennially',
		'regular_price' => '0',
		'sale_price' => '0',
		'price' => '0'
	)
);
$free_price_json = json_encode($free_price);
$free_subscriptions_query = mysqli_query($conn, "INSERT INTO subscriptions(name, time_created) VALUES('Free', '$time_created')");
$free_subscription_id = mysqli_insert_id($conn);
$free_subscription_meta = mysqli_query($conn, "INSERT INTO subscription_meta(subscription_id, meta_key, meta_value) VALUES
	('$free_subscription_id', 'price', '$free_price_json'),
	('$free_subscription_id', 'features', '$free_features_json'),
	('$free_subscription_id', 'recommend', '0')
	");

$starter_features = array(
	'email_accounts' => array(
		'title' => 'Email Account',
		'limit' => '5'
	),
	'tracking_email' => array(
		'title' => 'Email Tracking',
		'limit' => '5'
	),
	'urgent_email' => array(
		'title' => 'Urgent Email',
		'limit' => '5'
	),
	'staff_accounts' => array(
		'title' => 'Staff Account',
		'limit' => false
	),
	'email_templates' => array(
		'title' => 'Templates',
		'limit' => '5'
	),
	'data_email' => array(
		'title' => 'Data Email',
		'limit' => false
	),
	'api_integration' => array(
		'title' => 'API Integration',
		'limit' => false
	),
	'campaigns' => array(
		'title' => 'Campaigns/Month',
		'limit' => '5'
	)
);
$starter_features_json = json_encode($starter_features);
$starter_price = array(
	'currency' => array(
		'title' => 'Dollor',
		'slug' => 'USD',
		'symbol' => '$'
	),
	'monthly' => array(
		'title' => '1 Month',
		'regular_price' => '5',
		'sale_price' => '',
		'price' => '5'
	),
	'half_month' => array(
		'title' => '6 Months',
		'regular_price' => '30',
		'sale_price' => '0',
		'price' => '30'
	),
	'yearly' => array(
		'title' => 'Annually',
		'regular_price' => '60',
		'sale_price' => '55',
		'price' => '55'
	),
	'bi_year' => array(
		'title' => 'Biennially',
		'regular_price' => '120',
		'sale_price' => '110',
		'price' => '110'
	),
	'tri_year' => array(
		'title' => 'Triennially',
		'regular_price' => '180',
		'sale_price' => '160',
		'price' => '160'
	)
);
$starter_price_json = json_encode($starter_price);
$starter_subscriptions_query = mysqli_query($conn, "INSERT INTO subscriptions(name, time_created) VALUES('Starter', '$time_created')");
$starter_subscription_id = mysqli_insert_id($conn);
$starter_subscription_meta = mysqli_query($conn, "INSERT INTO subscription_meta(subscription_id, meta_key, meta_value) VALUES
	('$starter_subscription_id', 'price', '$starter_price_json'),
	('$starter_subscription_id', 'features', '$starter_features_json'),
	('$starter_subscription_id', 'recommend', '0')
	");

$pro_features = array(
	'email_accounts' => array(
		'title' => 'Email Account',
		'limit' => '10'
	),
	'tracking_email' => array(
		'title' => 'Email Tracking',
		'limit' => '-1'
	),
	'urgent_email' => array(
		'title' => 'Urgent Email',
		'limit' => '10'
	),
	'staff_accounts' => array(
		'title' => 'Staff Account',
		'limit' => '10'
	),
	'email_templates' => array(
		'title' => 'Templates',
		'limit' => '-1'
	),
	'data_email' => array(
		'title' => 'Data Email',
		'limit' => '10'
	),
	'api_integration' => array(
		'title' => 'API Integration',
		'limit' => true
	),
	'campaigns' => array(
		'title' => 'Campaigns/Month',
		'limit' => '-1'
	)
);
$pro_features_json = json_encode($pro_features);
$pro_price = array(
	'currency' => array(
		'title' => 'Dollor',
		'slug' => 'USD',
		'symbol' => '$'
	),
	'monthly' => array(
		'title' => '1 Month',
		'regular_price' => '10',
		'sale_price' => '',
		'price' => '10'
	),
	'half_month' => array(
		'title' => '6 Months',
		'regular_price' => '60',
		'sale_price' => '',
		'price' => '60'
	),
	'yearly' => array(
		'title' => 'Annually',
		'regular_price' => '120',
		'sale_price' => '110',
		'price' => '110'
	),
	'bi_year' => array(
		'title' => 'Biennially',
		'regular_price' => '240',
		'sale_price' => '200',
		'price' => '200'
	),
	'tri_year' => array(
		'title' => 'Triennially',
		'regular_price' => '360',
		'sale_price' => '360',
		'price' => '360'
	)
);
$pro_price_json = json_encode($pro_price);
$pro_subscriptions_query = mysqli_query($conn, "INSERT INTO subscriptions(name, time_created) VALUES('Pro', '$time_created')");
$pro_subscription_id = mysqli_insert_id($conn);
$pro_subscription_meta = mysqli_query($conn, "INSERT INTO subscription_meta(subscription_id, meta_key, meta_value) VALUES
	('$pro_subscription_id', 'price', '$pro_price_json'),
	('$pro_subscription_id', 'features', '$pro_features_json'),
	('$pro_subscription_id', 'recommend', '1')
	");

$business_features = array(
	'email_accounts' => array(
		'title' => 'Email Account',
		'limit' => '-1'
	),
	'tracking_email' => array(
		'title' => 'Email Tracking',
		'limit' => '-1'
	),
	'urgent_email' => array(
		'title' => 'Urgent Email',
		'limit' => '-1'
	),
	'staff_accounts' => array(
		'title' => 'Staff Account',
		'limit' => '-1'
	),
	'email_templates' => array(
		'title' => 'Templates',
		'limit' => '-1'
	),
	'data_email' => array(
		'title' => 'Data Email',
		'limit' => '-1'
	),
	'api_integration' => array(
		'title' => 'API Integration',
		'limit' => true
	),
	'campaigns' => array(
		'title' => 'Campaigns/Month',
		'limit' => '-1'
	)
);
$business_features_json = json_encode($business_features);
$business_price = array(
	'currency' => array(
		'title' => 'Dollor',
		'slug' => 'USD',
		'symbol' => '$'
	),
	'monthly' => array(
		'title' => '1 Month',
		'regular_price' => '20',
		'sale_price' => '',
		'price' => '20'
	),
	'half_month' => array(
		'title' => '6 Months',
		'regular_price' => '120',
		'sale_price' => '100',
		'price' => '100'
	),
	'yearly' => array(
		'title' => 'Annually',
		'regular_price' => '240',
		'sale_price' => '200',
		'price' => '200'
	),
	'bi_year' => array(
		'title' => 'Biennially',
		'regular_price' => '480',
		'sale_price' => '400',
		'price' => '400'
	),
	'tri_year' => array(
		'title' => 'Triennially',
		'regular_price' => '720',
		'sale_price' => '650',
		'price' => '650'
	)
);
$business_price_json = json_encode($business_price);
$business_subscriptions_query = mysqli_query($conn, "INSERT INTO subscriptions(name, time_created) VALUES('Business', '$time_created')");
$business_subscription_id = mysqli_insert_id($conn);
$business_subscription_meta = mysqli_query($conn, "INSERT INTO subscription_meta(subscription_id, meta_key, meta_value) VALUES
	('$business_subscription_id', 'price', '$business_price_json'),
	('$business_subscription_id', 'features', '$business_features_json'),
	('$business_subscription_id', 'recommend', '0')
	");*/


?>