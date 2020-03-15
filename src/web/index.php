<?php

use app\controllers\AccountController;
use app\controllers\AppController;
use app\controllers\MessageController;
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
$env->required(['DB_DRIVER', 'DB_HOST', 'DB_NAME', 'DB_USER', 'DB_PWD', 'DB_CHARSET', 'DB_COLLATION', 'DB_PREFIX', 'BT_ENV', 'BT_MERCHANT_ID', 'BT_PUBLIC_KEY', 'BT_PRIVATE_KEY']);

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

Braintree_Configuration::environment($_ENV['BT_ENV']);
Braintree_Configuration::merchantId($_ENV['BT_MERCHANT_ID']);
Braintree_Configuration::publicKey($_ENV['BT_PUBLIC_KEY']);
Braintree_Configuration::privateKey($_ENV['BT_PRIVATE_KEY']);

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
    $this->get('/order/confirm', OrderController::class . ':showOrder')->setName("showAddOrder");
    $this->get('/orders', OrderController::class . ':orders')->setName("showOrders");
    $this->get('/order/{id:[0-9]+}', OrderController::class . ':order')->setName("showOrder");
    $this->post('/order/place', OrderController::class . ':createOrder')->setName("addOrder");

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
    $this->post('/playlists', PlaylistController::class . ':importPlay')->setName("importPlay");

    //Supprimer une playliste
    $this->post('/playlists/{id:[0-9]+}', PlaylistController::class . ':delPlay')->setName("deletePlay");

    //Modifier une playliste
    $this->get('/playlists/update/{id:[0-9]+}', PlaylistController::class . ':formUpdatePlay')->setName("formUpdatePlay");
    $this->post('/playlists/update/{id:[0-9]+}', PlaylistController::class . ':updatePlay')->setName("updatePlay");

    //Ajouter une track à une playliste
    $this->get('/playlists/{id:[0-9]+}/addTrack', PlaylistController::class . ':formNewTrack')->setName("formNewTrack");
    $this->post('/playlists/{id:[0-9]+}/newTrack', PlaylistController::class . ':importTrackPlay')->setName("importTrackPlay");

    /**
     * Vinyles
     */
    $this->get('/vinyles', VinyleController::class . ':vinyles')->setName("showVinyles");
    $this->get('/vinyles/add', VinyleController::class . ':showAddVinyle')->setName("showAddVinyle");
    $this->get('/vinyle/{id:[0-9]+}', VinyleController::class . ':vinyle')->setName("showVinyle");
    $this->get('/vinyle/{id:[0-9]+}/delete', VinyleController::class . ':deleteVinyle')->setName("deleteVinyle");
    $this->get('/vinyle/{id:[0-9]+}/delete/{trackId:[0-9]+}', VinyleController::class . ':deleteAttachedTrack')->setName('deleteTrackVinyle');
    $this->post('/vinyles/add', VinyleController::class . ':addVinyle')->setName("addVinyle");
    $this->post('/vinyle/{id:[0-9]+}/add', VinyleController::class . ':addTracks')->setName("addTracksVinyle");
    $this->post('/vinyle/{id:[0-9]+}/update', VinyleController::class . ':updateVinyle')->setName("updateVinyle");

    /**
     * Tickets
     */

    $this->get('/tickets', TicketController::class . ':tickets')->setName("appTickets");
    $this->get('/newTicket', TicketController::class . ':newTicket')->setName("newTicket");
    $this->get('/closedTickets', TicketController::class . ':closedTickets')->setName("closedTickets");

    $this->post('/closeTicket/{id:[0-9]+}', TicketController::class . ':closeTicket')->setName("closeTicket");
    $this->post('/createTicket', TicketController::class . ':createTicket')->setName("createTicket");

    /**
     * Contact support
     */

    $this->get('/ticket/{id:[0-9]+}', MessageController::class . ':messages')->setName("contact");

})->add(new AuthMiddleware($container));

$app->run();