<?php

use app\config\Database;
use app\controllers\AccountController;
use app\controllers\AppController;
use app\controllers\Auth;
use app\controllers\HomeController;
use app\controllers\AuthController;
use app\controllers\ValidatorController;
use app\controllers\TracksController;
use app\extensions\TwigCsrf;
use app\extensions\TwigMessages;
use app\middlewares\OldInputMiddleware;
use Slim\App;
use Slim\Csrf\Guard;
use Slim\Flash\Messages;
use Slim\Http\Environment;
use Slim\Http\Uri;
use Slim\Views\Twig;
use Slim\Views\TwigExtension;

require_once(__DIR__ . '/vendor/autoload.php');

session_start();

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

$container['csrf'] = function () {
    $guard = new Guard();
    $guard->setPersistentTokenMode(true);
    return $guard;
};

$container['flash'] = function () {
    return new Messages();
};

$container['view'] = function ($container) {
    $view = new Twig(__DIR__ . '/app/views', [
        'cache' => false
    ]);

    $view->getEnvironment()->addGlobal('auth', [
        'check' => Auth::check(),
        'user' => Auth::user()
    ]);

    $view->addExtension(new TwigExtension($container->router, Uri::createFromEnvironment(new Environment($_SERVER))));
    $view->addExtension(new TwigMessages(new Messages()));
    $view->addExtension(new TwigCsrf($container->csrf));
    return $view;
};

$app->add(new OldInputMiddleware($container));
$app->add($container->csrf);

// Home
$app->get('/', HomeController::class . ':showHome')->setName('home');

// Users
$app->get('/validator', ValidatorController::class . ':validator')->setName('validator');

$app->get('/login', AuthController::class . ':showLogin')->setName('showLogin');
$app->post('/login', AuthController::class . ':login')->setName('login');

$app->get('/register', AuthController::class . ':showRegister')->setName('showRegister');
$app->post('/register', AuthController::class . ':register')->setName('register');

$app->get('/logout', AuthController::class . ':logout')->setName('logout');

$app->get('/account', AccountController::class . ':showAccount')->setName('showAccount');

//Library
$app->get('/home', AppController::class . ':showHome')->setName('appHome');

//Tracks
$app->get('/tracks', TracksController::class . ':tracks')->setName("appTracks");

$app->run();