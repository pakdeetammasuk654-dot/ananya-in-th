<?php
date_default_timezone_set('Asia/Bangkok'); // 👈 [เพิ่มบรรทัดนี้]
session_start();

error_reporting(E_ALL & ~E_DEPRECATED & ~E_STRICT & ~E_NOTICE);
ini_set('display_errors', 'Off');

require_once 'vendor/autoload.php';
require_once 'configs/constant.php';
require_once 'configs/config.php';


$app = new \Slim\App(['settings' => $config]);

$container = $app->getContainer();
$container['view'] = new \Slim\Views\PhpRenderer('./views');
$container['db'] = function ($c) {
    $db = $c['settings']['db'];
    $pdo = new PDO(
        "mysql:host=" . $db['host'] . ";dbname=" . $db['dbname'],
        $db['user'],
        $db['pass']
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $pdo->exec("set names utf8mb4");
    return $pdo;
};

require_once 'app/routes.php';

$app->run();

