<?php

namespace app\controllers;

use Slim\Container;
use Slim\Flash\Messages;
use Slim\Http\Response;
use Slim\Router;
use Slim\Views\Twig;

/**
 * Class Controller
 * @author Jules Sayer <jules.sayer@protonmail.com>
 * @abstract
 * @package mywishlist\controllers
 */
abstract class Controller {

    /**
     * @var Messages
     */
    protected $flash;

    /**
     * @var Twig
     */
    protected $view;

    /**
     * @var Router
     */
    protected $router;

    /**
     * Controller constructor.
     * @param $container
     */
    public function __construct(Container $container) {
        $this->flash = $container->flash;
        $this->view = $container->view;
        $this->router = $container->router;
    }

    /**
     * Twig Render
     * @param Response $response
     * @param String $file
     * @param array $params
     * @return mixed
     */
    protected function render(Response $response, String $file, array $params = []) {
        return $this->view->render($response, $file, $params);
    }

    protected function isConnected() {
        return isset($_SESSION['user']);
    }
}