<?php

namespace app\controllers;

use Slim\Container;

/**
 * Class Controller
 * @author Jules Sayer <jules.sayer@protonmail.com>
 * @abstract
 * @package mywishlist\controllers
 */
abstract class Controller {

    /**
     * @var Container
     */
    protected $container;

    /**
     * Controller constructor.
     * @param $container
     */
    public function __construct(Container $container) {
        $this->container = $container;
    }

    public function __get($property) {
        if($this->container->{$property}) {
            return $this->container->{$property};
        }
    }
}