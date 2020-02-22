<?php

namespace app\controllers;

use app\exceptions\AuthException;
use app\helpers\Auth;
use app\models\User;
use Slim\Http\Request;
use Slim\Http\Response;

/**
 * Class AuthController
 * @package app\controllers
 */
class AuthController extends Controller {

    public function showLogin(Request $request, Response $response, array $args): Response {
        $this->view->render($response, 'pages/login.twig');
        return $response;
    }

    public function logout(Request $request, Response $response, array $args): Response {
        Auth::logout();
        $this->flash->addMessage('success', "Vous avez été déconnecté.");
        $response = $response->withRedirect($this->router->pathFor('showLogin'));
        return $response;
    }

    public function login(Request $request, Response $response, array $args): Response {
        try {
            $login = filter_var($request->getParsedBodyParam('id'), FILTER_SANITIZE_STRING);
            $password = filter_var($request->getParsedBodyParam('password'), FILTER_SANITIZE_STRING);

            if(!Auth::attempt($login, $password)) throw new AuthException();

            $response = $response->withRedirect($this->router->pathFor('appHome'));
        } catch (AuthException $e) {
            $this->flash->addMessage('error', "Identifiant ou mot de passe invalide.");
            $response = $response->withRedirect($this->router->pathFor('showLogin'));
        }
        return $response;
    }

    public function showRegister(Request $request, Response $response, array $args): Response {
        $this->view->render($response, 'pages/register.twig');
        return $response;
    }

    public function register(Request $request, Response $response, array $args): Response {
        try {
            $name = filter_var($request->getParsedBodyParam('name'), FILTER_SANITIZE_STRING);
            $forename = filter_var($request->getParsedBodyParam('forename'), FILTER_SANITIZE_STRING);
            $pseudo = filter_var($request->getParsedBodyParam('pseudo'), FILTER_SANITIZE_STRING);
            $address = filter_var($request->getParsedBodyParam('address'), FILTER_SANITIZE_STRING);
            $email = filter_var($request->getParsedBodyParam('email'), FILTER_SANITIZE_EMAIL);
            $password = filter_var($request->getParsedBodyParam('password'), FILTER_SANITIZE_STRING);
            $password_conf = filter_var($request->getParsedBodyParam('password_conf'), FILTER_SANITIZE_STRING);

            if (is_null($request->getParsedBodyParam('agree'))) throw new AuthException("Vous devez accepter les conditions d'utilisation.");
            if (mb_strlen($pseudo, 'utf8') < 3 || mb_strlen($pseudo, 'utf8') > 35) throw new AuthException("Votre pseudo doit contenir entre 3 et 35 caractères.");
            if (mb_strlen($name, 'utf8') < 2 || mb_strlen($name, 'utf8') > 50) throw new AuthException("Votre nom doit contenir entre 2 et 50 caractères.");
            if (mb_strlen($forename, 'utf8') < 2 || mb_strlen($forename, 'utf8') > 50) throw new AuthException("Votre prénom doit contenir entre 2 et 50 caractères.");
            if (mb_strlen($password, 'utf8') < 8) throw new AuthException("Votre mot de passe doit contenir au moins 8 caractères.");
            if (User::where('pseudo', '=', $pseudo)->exists()) throw new AuthException("Ce pseudo est déjà pris.");
            if (User::where('email', '=', $email)->exists()) throw new AuthException("Cet email est déjà utilisée.");
            if ($password != $password_conf) throw new AuthException("La confirmation du mot de passe n'est pas bonne.");

            $user = new User();
            $user->nom = $name;
            $user->prenom = $forename;
            $user->pseudo = $pseudo;
            $user->email = $email;
            $user->address = $address;
            $user->password = password_hash($password_conf, PASSWORD_DEFAULT);
            $user->save();

            $this->flash->addMessage('success', "$pseudo, votre compte a été créé! Vous pouvez dès à présent vous connecter.");
            $response = $response->withRedirect($this->router->pathFor('showLogin'));
        } catch (AuthException $e) {
            $this->flash->addMessage('error', $e->getMessage());
            $response = $response->withRedirect($this->router->pathFor("showRegister"));
        }
        return $response;
    }
}