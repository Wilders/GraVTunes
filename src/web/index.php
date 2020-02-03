<?php

use app\config\Database;
use app\controllers\AccountController;
use app\controllers\AppController;
use app\controllers\BraintreeController;
use app\controllers\OrderController;
use app\helpers\Auth;
use app\controllers\CartController;
use app\controllers\HomeController;
use app\controllers\AuthController;
use app\controllers\ValidatorController;
use app\controllers\TracksController;
use app\extensions\TwigCsrf;
use app\extensions\TwigMessages;
use app\helpers\Basket;
use app\helpers\SessionBasket;
use app\middlewares\AuthMiddleware;
use app\middlewares\GuestMiddleware;
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
Braintree_Configuration::environment('sandbox');
Braintree_Configuration::merchantId('ydckbd3x4bg7c7tb');
Braintree_Configuration::publicKey('bhq4jpp6799jtv8z');
Braintree_Configuration::privateKey('fb3904f6c49af6f4f1417471db1fa5a9');

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

    $view->getEnvironment()->addGlobal('basket', [
        'all' => Basket::all(),
        'count' => Basket::count(),
        'subtotal' => Basket::subtotal()
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
$app->get('/validator', ValidatorController::class . ':validator')->setName('validator');

// Guest
$app->group('', function() {
    $this->get('/login', AuthController::class . ':showLogin')->setName('showLogin');
    $this->post('/login', AuthController::class . ':login')->setName('login');

    $this->get('/register', AuthController::class . ':showRegister')->setName('showRegister');
    $this->post('/register', AuthController::class . ':register')->setName('register');
})->add(new GuestMiddleware($container));

// Authenticated
$app->group('', function() {
    $this->get('/logout', AuthController::class . ':logout')->setName('logout');
    $this->get('/account', AccountController::class . ':showAccount')->setName('showAccount');
    $this->get('/home', AppController::class . ':showHome')->setName('appHome');

    $this->get('/cart', CartController::class . ':showCart')->setName("showCart");
    $this->get('/cart/add/{id:[0-9]+}[/{quantity:[0-9]+}]', CartController::class . ':addCart')->setName("addCart");
    $this->get('/cart/clear', CartController::class . ':clearCart')->setName("clearCart");
    $this->get('/cart/delete/{id:[0-9]+}', CartController::class . ':deleteCart')->setName("deleteCart");
    $this->post('/cart/update/{id:[0-9]+}', CartController::class . ':updateCart')->setName("updateCart");

    $this->get('/order', OrderController::class . ':showOrder')->setName("showOrder");
    $this->post('/order/create', OrderController::class . ':createOrder')->setName("createOrder");

    $this->get('/braintree/token', BraintreeController::class . ':token')->setName("braintreeToken");

    $this->get('/tracks', TracksController::class . ':tracks')->setName("appTracks");
    $this->get('/tracks/import', TracksController::class . ':importtracks')->setName("importTracks");

    $this->post('/importFile', TracksController::class . ':addFile')->setName("importFile");
    $this->post('/deleteFile', TracksController::class . ':deleteFile')->setName("deleteFile");
})->add(new AuthMiddleware($container));

$app->run();