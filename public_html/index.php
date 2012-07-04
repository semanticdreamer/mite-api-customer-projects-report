<?php
//relative path to application directory `app/`
define("APPDIR", '../app/');

//autoload dependencies managed by Composer
require APPDIR.'vendor/autoload.php';

//app configuration via Symfony's Yaml Component
// require APPDIR.'vendor/symfony/yaml/Symfony/Component/Yaml/Parser.php';
// require APPDIR.'vendor/symfony/yaml/Symfony/Component/Yaml/Inline.php';
// require APPDIR.'vendor/symfony/yaml/Symfony/Component/Yaml/Unescaper.php';
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

//Slim Custom View TwigView
$twigView = new View_Twig();
$twigView->twigDirectory = APPDIR.'vendor/twig/lib/twig';
$twigView->twigExtensions = array(new Twig_Extension_Debug(), new Twig_Extensions_Slim());
$twigView->twigOptions = array('debug' => true);

//With custom settings
$app = new Slim(array(
	// 'log.enable' => true,
	// 'log.path' => './log',
	// 'log.level' => 4,
	'view' => $twigView,
	'templates.path' => APPDIR.'templates'
));


//mite API
require APPDIR.'lib/mite/api.php';
$api = Mite_Api::getInstance();
$api->init($config['mite']['account'], $config['mite']['api_key']);

//routes using $app, $config, $api 
require APPDIR.'routes/account.php';
require APPDIR.'routes/project.php';

$app->get('/', function () use ($app) {
    $app->redirect('/accounts/');
});

$app->run();
?>