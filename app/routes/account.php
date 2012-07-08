<?php
//GET route for accounts
$app->get('/accounts/', function () use ($app, $config, $api) {
	$projects = json_decode($api->sendGetRequest('/projects.json'), true);
	$data = array(
		'title_postfix' => 'Accounts',
		'brand_url' => $app->urlFor('accounts'),
		'accounts' => $config['accounts']);
	$app->render('accounts.twig', $data);
})->name('accounts');

//GET route for account by id
$app->get('/accounts/:accountid/', function ($accountid) use ($app, $config, $api) {
	$authUser = $app->request()->headers('PHP_AUTH_USER');
	$miteCustomerName = $config['accounts'][$accountid]['mite_customer_name'];
	$informalSalutation = $config['accounts'][$accountid]['informal_salutation'];
	$customer = json_decode($api->sendGetRequest('/customers.json?name='.urlencode($miteCustomerName)), true);
	$projects = json_decode($api->sendGetRequest('/projects.json'), true);
	$projectsForAccount = Mite_Api::getResourcesByKeyValue('project', 
		'customer_name', $miteCustomerName, $projects);
	$data = array(
		'title_postfix' => 'Account ' . $miteCustomerName,
		'brand_url' => array_key_exists($authUser, $config['admin_users']) ? $app->urlFor('accounts') : $app->urlFor('account', array('accountid' => $accountid)),
		'account_name' => $miteCustomerName,
		'account_salutation' => $informalSalutation,
		'account' => $customer,
		'account_id' => $accountid,
		'projects' => $projectsForAccount);
	$app->render('account.twig', $data);
})->name('account');
?>