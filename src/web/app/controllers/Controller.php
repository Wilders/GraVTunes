<?php

namespace app\controllers;

use Slim\Container;

/**
 * Class Controller
 * @abstract
 * @package app\controllers
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

    /**
     * Controller magic getter
     *
     * @param $property
     * @return mixed
     */
    public function __get($property) {
        if($this->container->{$property}) {
            return $this->container->{$property};
        }
    }
}