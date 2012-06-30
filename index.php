<?php
require './Slim/Slim/Slim.php';
//With default settings
$app = new Slim();

//With custom settings
$app = new Slim(array(
    'log.enable' => true,
    'log.path' => './logs',
    'log.level' => 4
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