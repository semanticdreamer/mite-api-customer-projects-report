<?php
//GET route for '/'
$app->get('/', function () use ($app, $config) {
	$data = array(
		'brand' => $config['app']['title']
	);
	$app->render('welcome.twig', $data);
})->name('welcome');;
?>