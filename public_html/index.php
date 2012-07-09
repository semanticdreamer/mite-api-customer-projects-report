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
$twigView->twigOptions = array('mode' => $config['slim_framework']['settings']['mode']);

//app, with custom settings
$app = new Slim(array(
	'view' => $twigView,
	'templates.path' => APPDIR.'templates'
));

//app mode 'production'
$app->configureMode('production', function () use ($app) {
    $app->config(array(
        'log.enable' => false,
        'debug' => false
    ));
});

//app mode 'development'
$app->configureMode('development', function () use ($app) {
    $app->config(array(
        'log.enable' => true,
        'debug' => true
    ));
});

//mite API
require APPDIR.'lib/mite/api.php';
$api = Mite_Api::getInstance();
$api->init($config['mite']['account'], $config['mite']['api_key']);

//app-wide route conditions
$accountIdConditionRegex = '(' . implode('|', array_keys($config['accounts'])) . ')';
Slim_Route::setDefaultConditions(array(
    'accountid' => $accountIdConditionRegex
));

//authentication, require HTTP basic auth for all routes
require APPDIR.'lib/slim/Middleware/Auth/HttpBasicWithAuthZ.php';
$authConfig = array_merge($config['accounts'], $config['slim_framework']['authentication']['admin_users'], $config['slim_framework']['authorization']);
$app->add(new Slim_Middleware_Auth_HttpBasicWithAuthZ($authConfig));

//routes using $app, $config, $api 
require APPDIR.'routes/account.php';
require APPDIR.'routes/account_project.php';

//GET route for '/' (index)
$app->get('/', function () use ($app, $config) {
	$app->render('index.twig');
})->name('index');

//global variable 'auth_user'
$twig = $app->view()->getEnvironment();
$twig->addGlobal('auth_user', $app->request()->headers('PHP_AUTH_USER'));
$twig->addGlobal('title', $config['title']);
$twig->addGlobal('brand', $config['brand']);
$twig->addGlobal('support_email', $config['support_email']);
$twig->addGlobal('brand_url', $app->urlFor('index'));

$app->run();
?>