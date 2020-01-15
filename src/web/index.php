<?php

use app\config\Database;
use app\controllers\HomeController;
use app\controllers\UserController;
use app\controllers\ValidatorController;
use app\extensions\TwigMessages;
use Slim\App;
use Slim\Flash\Messages;
use Slim\Http\Environment;
use Slim\Http\Uri;
use Slim\Views\Twig;
use Slim\Views\TwigExtension;

session_start();

require_once(__DIR__ . '/vendor/autoload.php');

try {
    Database::connect();
} catch (Exception $e) {
    die($e->getMessage());
}

$config = [
    'settings' => [
        'displayErrorDetails' => 1,
    ],
];

$app = new App($config);
$container = $app->getContainer();

$container['view'] = function ($container) {

    $view = new Twig(__DIR__ . '/app/views', [
        'cache' => false
    ]);

    $view->addExtension(new TwigExtension($container->router, Uri::createFromEnvironment(new Environment($_SERVER))));
    $view->addExtension(new TwigMessages(new Messages()));
    return $view;
};

$container['flash'] = function () {
    return new Messages();
};

// Home
$app->get('/', HomeController::class . ':showHome')->setName('home');

// Users
$app->get('/uac/validator', ValidatorController::class . ':validator')->setName('validator');

$app->get('/uac/login', UserController::class . ':showLogin')->setName('showLogin');
$app->post('/uac/login', UserController::class . ':login')->setName('login');

$app->get('/uac/register', UserController::class . ':showRegister')->setName('showRegister');
$app->post('/uac/register', UserController::class . ':register')->setName('register');

$app->get('/uac/forgot', UserController::class . ':showForgot')->setName('showForgot');
$app->post('/uac/forgot', UserController::class . ':forgot')->setName('forgot');

$app->get('/uac/reset', UserController::class . ':showReset')->setName('showReset');
$app->post('/uac/reset', UserController::class . ':reset')->setName('reset');

$app->get('/app/account', UserController::class . ':showReset')->setName('showAccount');

$app->run();