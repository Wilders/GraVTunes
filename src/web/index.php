<?php

use app\config\Database;
use app\controllers\HomeController;
use app\extensions\TwigMessages;
use Slim\App;
use Slim\Flash\Messages;
use Slim\Http\Environment;
use Slim\Http\Uri;
use Slim\Views\Twig;
use Slim\Views\TwigExtension;

session_start();

require_once(__DIR__ . '/vendor/autoload.php');

/**
 * Connexion à la base de donnée
 */
try {
    Database::connect();
} catch (Exception $e) {
    die($e->getMessage());
}

/**
 * Affichage des erreurs en détail
 */
$config = [
    'settings' => [
        'displayErrorDetails' => 1,
    ],
];

/**
 * Instanciation de Slim
 */
$app = new App($config);
$container = $app->getContainer();


/**
 * Paramétrage du container avec Twig
 * @param $container
 * @return Twig
 */
$container['view'] = function ($container) {

    $view = new Twig(__DIR__ . '/app/views', [
        'cache' => false
    ]);

    $view->addExtension(new TwigExtension($container->router, Uri::createFromEnvironment(new Environment($_SERVER))));
    $view->addExtension(new TwigMessages(new Messages()));
    return $view;
};

/**
 * Paramétrage du container avec Slim Flash Messages
 * @return Messages
 */
$container['flash'] = function () {
    return new Messages();
};


/**
 * Routes
 */

// Home
$app->get('/', HomeController::class . ':showHome')->setName('home');

$app->run();