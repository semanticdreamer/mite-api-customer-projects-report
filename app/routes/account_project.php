<?php
//GET route for project by id
$app->get('/accounts/:accountid/projects/:projectid/', function ($accountid, $projectid) use ($app, $config, $api) {
	$project = json_decode($api->sendGetRequest('/projects/'.$projectid.'.json'), true);
	$time_entries = json_decode($api->sendGetRequest('/time_entries.json?project_id='.$projectid), true);
	$data = array(
		'brand' => $config['site']['title'],
		'project' => $project,
		'time_entries' => $time_entries);
	$app->render('project.twig', $data);
})->name('project');
?>