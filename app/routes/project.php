<?php
//GET route for project by id
$app->get('/projects/:id/', function ($id) use ($app, $config, $api) {
	$project = json_decode($api->sendGetRequest('/projects/'.$id.'.json'));
	$time_entries = json_decode($api->sendGetRequest('/time_entries.json?project_id='.$id));
	$data = array(
		'brand' => $config['site']['title'],
		'project' => $project,
		'time_entries' => $time_entries);
	$app->render('project.twig', $data);
})->name('project');
?>