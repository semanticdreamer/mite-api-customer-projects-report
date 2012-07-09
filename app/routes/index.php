<?php
//GET route for '/' (index)
$app->get('/', function () use ($app, $config) {
	$app->render('index.twig');
})->name('index');
?>