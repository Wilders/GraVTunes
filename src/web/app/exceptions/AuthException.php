<?php

namespace app\exceptions;

use Exception;
use Throwable;

class AuthException extends Exception {

    private $redirectRoute;

    public function __construct($message = "", $route, $code = 0, Throwable $previous = null) {
        parent::__construct($message, $code, $previous);
        $this->redirectRoute = $route;
    }

    public function getRoute() {
        return $this->redirectRoute;
    }
}