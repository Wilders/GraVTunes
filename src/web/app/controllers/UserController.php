<?php

namespace app\controllers;

use app\exceptions\AuthException;
use app\models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
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

    public function login(Request $request, Response $response, array $args): Response {
        try {
            if($this->isConnected()) throw new AuthException("Vous ne devez pas déjà être connecté pour effectuer cette action", "showAccount");
            $login = filter_var($request->getParsedBodyParam('id'), FILTER_SANITIZE_STRING);
            $password = filter_var($request->getParsedBodyParam('password'), FILTER_SANITIZE_STRING);

            $user = User::where('pseudo', '=', $login)->orWhere('email', '=', $login)->firstOrFail();
            if(!password_verify($password, $user->password)) throw new AuthException('Votre mot de passe est incorrect', "showLogin");

            $_SESSION['user'] = $user;

            $response = $response->withRedirect($this->router->pathFor('showAccount'));
        } catch (ModelNotFoundException $e) {
            $this->flash->addMessage('error', 'Aucun compte associé à cet identifiant n\'a été trouvé.');
            $response = $response->withRedirect($this->router->pathFor('showLogin'));
        } catch (AuthException $e) {
            $this->flash->addMessage('error', $e->getMessage());
            $response = $response->withRedirect($this->router->pathFor($e->getRoute()));
        }
        return $response;
    }

    public function showRegister(Request $request, Response $response, array $args): Response {
        $this->render($response, 'pages/register.twig');
        return $response;
    }

    public function register(Request $request, Response $response, array $args): Response {
        try {
        } catch (\Exception $e) {}
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