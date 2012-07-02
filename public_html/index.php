<?php
//relative path to application directory `app/`
define("APPDIR", '../app/');

//autoload dependencies managed by Composer
require APPDIR.'vendor/autoload.php';

//app configuration via Symfony's Yaml Component
//require APPDIR.'vendor/symfony/yaml/Symfony/Component/Yaml/Parser.php';
use Symfony\Component\Yaml\Parser;
use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Yaml;
$yaml = new Parser();
$config = null;
try {
    $config = $yaml->parse(file_get_contents(APPDIR.'config.yml'));
} catch (ParseException $e) {
    printf("Unable to parse the YAML string: %s", $e->getMessage());
}

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

//GET config
// $app->get('/config', function () use ($config) {
// 	echo Yaml::dump($config);
// });

$app->run();
?>