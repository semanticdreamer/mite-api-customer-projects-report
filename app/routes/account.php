<?php
//GET route for account
$app->get('/accounts/:id/', function ($id) use ($config, $api) {
	//load mite customer
	$response = $api->sendGetRequest('/customers.json');
	$miteCustomerName = $config['accounts'][$id]['mite_customer_name'];
	$results = Mite_Api::getResourceByKeyValue('customer', 
		'name', $miteCustomerName, json_decode($response, true));
	var_dump($results);	
});
?>