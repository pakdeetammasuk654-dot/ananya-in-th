<?php
date_default_timezone_set('Asia/Bangkok');
session_start();

error_reporting(E_ALL & ~E_DEPRECATED & ~E_STRICT & ~E_NOTICE);
ini_set('display_errors', 'Off');

use DI\Container;
use Slim\Factory\AppFactory;
use Slim\Views\PhpRenderer;

require_once 'vendor/autoload.php';
require_once 'configs/constant.php';
require_once 'configs/config.php';

// Create Container using PHP-DI
$container = new Container();

// Configure Container
$container->set('db', function () use ($config) {
    $db = $config['db'];
    $pdo = new PDO(
        "mysql:host=" . $db['host'] . ";dbname=" . $db['dbname'],
        $db['user'],
        $db['pass']
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $pdo->exec("set names utf8mb4");
    return $pdo;
});

// View
$container->set('view', function () {
    return new PhpRenderer('./views');
});

// Set container to AppFactory
AppFactory::setContainer($container);
$app = AppFactory::create();

// Add Body Parsing Middleware
$app->addBodyParsingMiddleware();

// Add Routing Middleware
$app->addRoutingMiddleware();

// Add Error Middleware
$errorMiddleware = $app->addErrorMiddleware(true, true, true);

// Register container itself for injection into legacy Managers
$container->set('container', function () use ($container) {
    return $container;
});

// Routes
require_once 'app/routes.php';

$app->run();

