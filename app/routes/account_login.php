<?php
//GET route login
$app->get('/accounts/login/', function () {

})->name('login');

//GET route for redirect after successfull login
$app->get('/accounts/loggedin/:authuser/', function ($authuser) use ($app, $config) {
	$reqAuthUser = $app->request()->headers('PHP_AUTH_USER');
	if (!isset($authuser) || $authuser !== $reqAuthUser) {
		$app->redirect($app->urlFor('index'));
	} elseif (array_key_exists($reqAuthUser, $config['slim_framework']['authentication']['admin_users'])) {
		//redirect admin user to account list
		$app->redirect($app->urlFor('accounts'));
	} else {
		//redirect account user to account info
		// $app->redirect($app->urlFor('account', array('accountid', $reqAuthUser)));
		$app->redirect('/accounts/'.$reqAuthUser.'/');		
	}
})->name('loggedin');
?>