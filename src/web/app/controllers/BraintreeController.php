<?php

namespace app\controllers;

use Braintree_ClientToken;
use Slim\Http\Request;
use Slim\Http\Response;

/**
 * Class BraintreeController
 * @package app\controllers
 */
class BraintreeController extends Controller {

    public function token(Request $request, Response $response, array $args): Response {
        return $response->withJson([
            'token' => Braintree_ClientToken::generate()
        ]);
    }
}