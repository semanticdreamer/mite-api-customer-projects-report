<?php
//GET route for '/login/'
$app->get('/login/', function () use ($app, $config) {
	$app->redirect('/accounts/');
})->name('login');;
?>