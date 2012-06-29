<?php
require 'Slim/Slim.php';
$app = new Slim();
$app->get('/hello/:name', function ($name) {
    echo "Hello, $name!";
});
$app->run();
?>