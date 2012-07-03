<?php
//GET route for account
$app->get('/accounts/:id/', function ($id) use ($app, $config, $api) {
	//load mite customer
	$response = $api->sendGetRequest('/customers.json');
	$miteCustomerName = $config['accounts'][$id]['mite_customer_name'];
	$customer = Mite_Api::getResourceByKeyValue('customer', 
		'name', $miteCustomerName, json_decode($response, true));
	//var_dump($customer);
	$data = array(
		'brand' => $config['site']['title'],
		'header_partial' => file_get_contents(APPDIR.'templates/partials/header.mustache'),
		'footer_partial' => file_get_contents(APPDIR.'templates/partials/footer.mustache'),
		'headline' => 'Account Details', 
		'account_name' => $customer['name']);
	$app->render('account.mustache', $data);
});
?>