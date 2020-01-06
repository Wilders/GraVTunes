<?php

namespace app\controllers;

use Slim\Http\Request;
use Slim\Http\Response;

/**
 * Class UserController
 * @author Jules Sayer <jules.sayer@protonmail.com>
 * @package app\controllers
 */
class UserController extends Controller {

    public function showLogin(Request $request, Response $response, array $args): Response {
        $this->render($response, 'pages/login.twig');
        return $response;
    }

    public function showRegister(Request $request, Response $response, array $args): Response {
        $this->render($response, 'pages/register.twig');
        return $response;
    }

    public function showForgot(Request $request, Response $response, array $args): Response {
        $this->render($response, 'pages/forgot.twig');
        return $response;
    }

    public function showReset(Request $request, Response $response, array $args): Response {
        $this->render($response, 'pages/reset.twig');
        return $response;
    }
}