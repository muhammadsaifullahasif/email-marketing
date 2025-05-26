<?php

$home_url = 'http://localhost:8080/email-marketing/';
$dashboard_url = 'http://localhost:8080/email-marketing/dashboard/';
$api_url = 'http://localhost:8080/email-marketing/api/';

$conn = mysqli_connect('localhost', 'root', '', 'email_marketing');

// session_start();

// $time_created = time();

$roles = array(
	'accounts' => array(
		'add' => 1, 
		'edit' => 1,
		'delete' => 1
	), 
	'campaigns' => array(
		'add' => 1, 
		'edit' => 1,
		'delete' => 1
	), 
	'inbox' => array(
		'read' => 1,
		'schedule' => 1,
		'sent' => 1,
		'trash' => 1,
		'urgent_email' => 1,
		'tracking_email' => 1
	),
	'contact_lists' => array(
		'add' => 1,
		'edit' => 1,
		'delete' => 1
	),
	'templates' => array(
		'add' => 1,
		'edit' => 1,
		'delete' => 1
	)
);

// echo json_encode($roles);

$features = array(
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

$features_json = json_encode($features);

$price = array(
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

// $price_json = json_encode($price);

/*$subscriptions_query = mysqli_query($conn, "INSERT INTO subscriptions(name, time_created) VALUES('Pro', '$time_created')");

$subscription_id = mysqli_insert_id($conn);

$subscription_meta = mysqli_query($conn, "INSERT INTO subscription_meta(subscription_id, meta_key, meta_value) VALUES
	('$subscription_id', 'price', '$price_json'),
	('$subscription_id', 'features', '$features_json'),
	('$subscription_id', 'recommend', '0')
	");*/

?>