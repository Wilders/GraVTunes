<?php

namespace app\controllers;

use Braintree_ClientToken;
use Slim\Http\Request;
use Slim\Http\Response;

/**
 * Class HomeController
 * @author Jules Sayer <jules.sayer@protonmail.com>
 * @package mywishlist\controllers
 */
class BraintreeController extends Controller {

    /**
     * Appel index.twig
     *
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     */
    public function token(Request $request, Response $response, array $args): Response {
        return $response->withJson([
            'token' => Braintree_ClientToken::generate()
        ]);
    }
}