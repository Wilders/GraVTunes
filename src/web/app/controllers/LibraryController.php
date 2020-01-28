<?php

namespace app\controllers;

use Slim\Container;
use Slim\Http\Request;
use Slim\Http\Response;

/**
 * Class LibraryController
 * @author Anthony Pernot <anthony.pernot@hotmail.fr>
 * @package app\controllers
 */
class LibraryController extends Controller{

    public function showLibrary(Request $request, Response $response, array $args ) : Response {
        $this->view->render($response, 'pages/library.twig');
        return $response;
    }

}

?>