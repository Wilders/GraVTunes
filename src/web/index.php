<?php

use app\controllers\AccountController;
use app\controllers\AppController;
use app\controllers\OrderController;
use app\helpers\Auth;
use app\controllers\CartController;
use app\controllers\AuthController;
use app\controllers\ValidatorController;
use app\controllers\TracksController;
use app\controllers\PlaylistController;
use app\controllers\VinyleController;
use app\controllers\TicketController;
use app\extensions\TwigCsrf;
use app\extensions\TwigMessages;
use app\helpers\Basket;
use app\middlewares\AuthMiddleware;
use app\middlewares\GuestMiddleware;
use app\middlewares\OldInputMiddleware;
use Illuminate\Database\Capsule\Manager as DB;
use Slim\App;
use Slim\Csrf\Guard;
use Slim\Flash\Messages;
use Slim\Http\Environment;
use Slim\Http\Uri;
use Slim\Views\Twig;
use Slim\Views\TwigExtension;

require_once(__DIR__ . '/vendor/autoload.php');

session_start();

$env = Dotenv\Dotenv::createImmutable(__DIR__);
$env->load();
$env->required(['DB_DRIVER', 'DB_HOST', 'DB_NAME', 'DB_USER', 'DB_PWD', 'DB_CHARSET', 'DB_COLLATION', 'DB_PREFIX']);

$db = new DB();
$db->addConnection([
    'driver' => $_ENV['DB_DRIVER'],
    'host' => $_ENV['DB_HOST'],
    'database' => $_ENV['DB_NAME'],
    'username' => $_ENV['DB_USER'],
    'password' => $_ENV['DB_PWD'],
    'charset' => $_ENV['DB_CHARSET'],
    'collation' => $_ENV['DB_COLLATION'],
    'prefix' => $_ENV['DB_PREFIX']
]);
$db->setAsGlobal();
$db->bootEloquent();

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

$container['uploadsPath'] = __DIR__ . DIRECTORY_SEPARATOR . 'uploads';

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
$app->get('/', AppController::class . ':showHome')->setName('home');
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

    $this->get('/home', AppController::class . ':showDashHome')->setName('appHome');
    $this->get('/braintree/token', AppController::class . ':btToken')->setName("braintreeToken");

    /**
     * Account
     */
    $this->get('/logout', AuthController::class . ':logout')->setName('logout');
    $this->get('/account', AccountController::class . ':showAccount')->setName('showAccount');
    $this->post('/account/settings', AccountController::class . ':updateSettings')->setName('updateSettings');
    $this->post('/account/profile', AccountController::class . ':updateProfile')->setName('updateProfile');
    $this->post('/account/security', AccountController::class . ':updateSecurity')->setName('updateSecurity');

    /**
     * Cart
     */
    $this->get('/cart', CartController::class . ':showCart')->setName("showCart");
    $this->get('/cart/add/{id:[0-9]+}[/{quantity:[0-9]+}]', CartController::class . ':addCart')->setName("addCart");
    $this->get('/cart/clear', CartController::class . ':clearCart')->setName("clearCart");
    $this->get('/cart/delete/{id:[0-9]+}', CartController::class . ':deleteCart')->setName("deleteCart");
    $this->post('/cart/update/{id:[0-9]+}', CartController::class . ':updateCart')->setName("updateCart");

    /**
     * Order
     */
    $this->get('/order', OrderController::class . ':showOrder')->setName("showOrder");
    $this->post('/order/create', OrderController::class . ':createOrder')->setName("createOrder");

    /**
     * Tracks
     */
    $this->get('/tracks', TracksController::class . ':tracks')->setName("appTracks");
    $this->get('/tracks/import', TracksController::class . ':importTracks')->setName("importTracks");
    $this->get('/tracks/update/{id:[0-9]+}', TracksController::class . ':formUpdateTracks')->setName("formUpdateTracks");

    $this->post('/importFile', TracksController::class . ':addFile')->setName("importFile");
    $this->post('/updateFile/{id:[0-9]+}', TracksController::class . ':updateFile')->setName("updateFile");
    $this->post('/deleteFile/{id:[0-9]+}', TracksController::class . ':deleteFile')->setName("deleteFile");

    /**
     * Playlists
     */
    $this->get('/playlists', PlaylistController::class . ':playlists')->setName("appPlaylist");

    //Ajouter une playliste
    $this->get('/playlists/addPlaylist', PlaylistController::class . ':newPlay')->setName("newPlay");
    $this->post('/addPlaylist', PlaylistController::class . ':importPlay')->setName("importPlay");

    //Supprimer une playliste
    $this->post('/playlists/{id:[0-9]+}', PlaylistController::class . ':delPlay')->setName("deletePlay");

    //Modifier une playliste
    $this->get('/playlists/update/{id:[0-9]+}', PlaylistController::class . ':formUpdatePlay')->setName("formUpdatePlay");
    $this->post('/playlists/update/{id:[0-9]+}', PlaylistController::class . ':updatePlay')->setName("updatePlay");

    /**
     * Vinyles
     */
    $this->get('/vinyles', VinyleController::class . ':vinyles')->setName("appVinyle");
    $this->get('/vinyles/add', VinyleController::class . ':addVinyle')->setName("addVinyle");

    /**
     * Tickets
     */

    $this->get('/tickets', TicketController::class . ':tickets')->setName("appTickets");
    $this->get('/newTicket', TicketController::class . ':newTicket')->setName("newTicket");
    $this->get('/closedTickets', TicketController::class . ':closedTickets')->setName("closedTickets");

    $this->post('/closeTicket/{id:[0-9]+}', TicketController::class . ':closeTicket')->setName("closeTicket");
    $this->post('/createTicket', TicketController::class . ':createTicket')->setName("createTicket");
})->add(new AuthMiddleware($container));

$app->run();