<?php

namespace App\controllers;

use Slim\Http\Request;
use Slim\Http\Response;

/**
 * Class HomeController
 * @author Jules Sayer <jules.sayer@protonmail.com>
 * @package mywishlist\controllers
 */
class HomeController extends Controller {

    /**
     * Appel home.phtml, permet d'afficher les accueils
     * et les messages flash.
     *
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     */
    public function showHome(Request $request, Response $response, array $args): Response {
        $this->render($response, 'pages/home.twig');
        return $response;
    }
}