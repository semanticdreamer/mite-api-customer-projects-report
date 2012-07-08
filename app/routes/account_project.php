<?php

//GET route for projects
$app->get('/accounts/:accountid/projects/', function ($accountid) use ($app, $config, $api) {
	$authUser = $app->request()->headers('PHP_AUTH_USER');
	$miteCustomerName = $config['accounts'][$accountid]['mite_customer_name'];
	$projects = json_decode($api->sendGetRequest('/projects.json'), true);
	$projectsForAccount = Mite_Api::getResourcesByKeyValue('project', 
		'customer_name', $miteCustomerName, $projects);
	$data = array(
		'title_postfix' => 'Projects ' . $miteCustomerName,
		'brand_url' => array_key_exists($authUser, $config['admin_users']) ? $app->urlFor('accounts') : $app->urlFor('account', array('accountid' => $accountid)),
		'account_name' => $miteCustomerName,
		'account_id' => $accountid,
		'projects' => $projectsForAccount);
	$app->render('projects.twig', $data);
})->name('projects');

//GET route for project by id
$app->get('/accounts/:accountid/projects/:projectid/', function ($accountid, $projectid) use ($app, $config, $api) {
	$authUser = $app->request()->headers('PHP_AUTH_USER');
	$miteCustomerName = $config['accounts'][$accountid]['mite_customer_name'];
	$projects = json_decode($api->sendGetRequest('/projects.json'), true);
	$projectsForAccount = Mite_Api::getResourcesByKeyValue('project', 
		'customer_name', $miteCustomerName, $projects);
	$project = json_decode($api->sendGetRequest('/projects/'.$projectid.'.json'), true);
	$time_entries = json_decode($api->sendGetRequest('/time_entries.json?project_id='.$projectid), true);
	$data = array(
		'title_postfix' => 'Project ' . $project['project']['name'],
		'brand_url' => array_key_exists($authUser, $config['admin_users']) ? $app->urlFor('accounts') : $app->urlFor('account', array('accountid' => $accountid)),
		'project' => $project,
		'projects' => $projectsForAccount,
		'account_name' => $miteCustomerName,
		'account_id' => $accountid,
		'time_entries' => $time_entries);
	$app->render('project.twig', $data);
})->name('project');
?>