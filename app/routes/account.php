<?php
//GET route for accounts
$app->get('/accounts/', function () use ($app, $config, $api) {
	$data = array(
		'brand' => $config['site']['title'],
		'accounts' => $config['accounts']);
	$app->render('accounts.twig', $data);
})->name('accounts');

//GET route for account by id
$app->get('/accounts/:id/', function ($id) use ($app, $config, $api) {
	$miteCustomerName = $config['accounts'][$id]['mite_customer_name'];
	$projects = $api->sendGetRequest('/projects.json');
	$projectsForAccount = Mite_Api::getResourcesByKeyValue('project', 
		'customer_name', $miteCustomerName, json_decode($projects, true));
	$data = array(
		'brand' => $config['site']['title'],
		'account_name' => $miteCustomerName,
		'projects' => $projectsForAccount);
	$app->render('account.twig', $data);
})->name('account');
?>