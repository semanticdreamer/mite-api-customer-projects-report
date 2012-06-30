<?php
//relative path to application directory `app/`
define("APPDIR", '../app/');

//require 'vendor/autoload.php';
//Slim Framework
require APPDIR.'vendor/slim/slim/Slim/Slim.php';

//Slim Custom View MustacheView
require APPDIR.'vendor/slim/extras/Views/MustacheView.php';
MustacheView::$mustacheDirectory = APPDIR.'vendor/phly/library/Phly/Mustache/';

//With default settings
$app = new Slim();

//With custom settings
$app = new Slim(array(
    'log.enable' => true,
    'log.path' => './log',
    'log.level' => 4,
    'view' => 'MustacheView'
));

//GET route
$app->get('/hello/:name', function ($name) {
    echo "Hello, $name";
});

//POST route
$app->post('/person', function () {
    //Create new Person
});

//PUT route
$app->put('/person/:id', function ($id) {
    //Update Person identified by $id
});

//DELETE route
$app->delete('/person/:id', function ($id) {
    //Delete Person identified by $id
});

$app->run();
?>