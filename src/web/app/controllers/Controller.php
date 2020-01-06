<?php

namespace app\controllers;

use Slim\Container;
use Slim\Http\Response;

/**
 * Class Controller
 * @author Jules Sayer <jules.sayer@protonmail.com>
 * @abstract
 * @package mywishlist\controllers
 */
abstract class Controller {

    /**
     * @var Container Slim's container
     */
    private $container;

    /**
     * Controller constructor.
     * @param $container
     */
    public function __construct(Container $container) {
        $this->container = $container;
    }

    /**
     * Twig Render
     * @param Response $response
     * @param String $file
     * @param array $params
     * @return mixed
     */
    protected function render(Response $response, String $file, array $params = []) {
        return $this->container->view->render($response, $file, $params);
    }
}