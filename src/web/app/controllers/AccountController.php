<?php

namespace app\controllers;

use app\exceptions\AuthException;
use app\helpers\Auth;
use app\models\User;
use Slim\Http\Request;
use Slim\Http\Response;

/**
 * Class AccountController
 * @package app\controllers
 */
class AccountController extends Controller {

    public function showAccount(Request $request, Response $response, array $args): Response {
        $this->view->render($response, 'pages/account.twig');
        return $response;
    }

    public function updateSettings(Request $request, Response $response, array $args): Response {
        try {
            $name = filter_var($request->getParsedBodyParam('name'), FILTER_SANITIZE_STRING);
            $forename = filter_var($request->getParsedBodyParam('forename'), FILTER_SANITIZE_STRING);
            $address = filter_var($request->getParsedBodyParam('address'), FILTER_SANITIZE_STRING);
            $email = filter_var($request->getParsedBodyParam('email'), FILTER_SANITIZE_EMAIL);

            if (mb_strlen($name, 'utf8') < 2 || mb_strlen($name, 'utf8') > 50) throw new AuthException("Votre nom doit contenir entre 2 et 50 caractères.");
            if (mb_strlen($forename, 'utf8') < 2 || mb_strlen($forename, 'utf8') > 50) throw new AuthException("Votre prénom doit contenir entre 2 et 50 caractères.");

            if(Auth::user()->email != $email) {
                if (User::where('email', '=', $email)->exists()) throw new AuthException("Cet email est déjà utilisée.");
            }

            $user = Auth::user();
            $user->nom = $name;
            $user->prenom = $forename;
            $user->email = $email;
            $user->address = $address;
            $user->save();

            $this->flash->addMessage('success', "Les modifications apportées à votre compte ont été enregistrées !");
            $response = $response->withRedirect($this->router->pathFor('showAccount'));
        } catch(AuthException $e) {
            $this->flash->addMessage('error', $e->getMessage());
            $response = $response->withRedirect($this->router->pathFor("showAccount"));
        }
        return $response;
    }

    public function updateProfile(Request $request, Response $response, array $args): Response {
        try {
            /**
             * Modification avatar
             */
        } catch (AuthException $e) {
            $this->flash->addMessage('error', $e->getMessage());
            $response = $response->withRedirect($this->router->pathFor("showAccount"));
        }
        return $response;
    }

    public function updateSecurity(Request $request, Response $response, array $args): Response {
        try {
            $actualpassword = filter_var($request->getParsedBodyParam('actual_password'), FILTER_SANITIZE_STRING);
            $newpassword = filter_var($request->getParsedBodyParam('newpassword'), FILTER_SANITIZE_STRING);
            $confnewpassword = filter_var($request->getParsedBodyParam('conf_newpassword'), FILTER_SANITIZE_STRING);

            if(!password_verify($actualpassword, Auth::user()->password)) throw new AuthException("Le mot de passe actuel n'est pas bon.");
            if (mb_strlen($newpassword, 'utf8') < 8) throw new AuthException("Votre nouveau mot de passe doit contenir au moins 8 caractères.");
            if ($newpassword != $confnewpassword) throw new AuthException("La confirmation du mot de passe n'est pas bonne.");

            $user = Auth::user();
            $user->password = password_hash($confnewpassword, PASSWORD_DEFAULT);
            $user->save();

            $this->flash->addMessage('success', "Les modifications apportées à votre compte ont été enregistrées !");
            $response = $response->withRedirect($this->router->pathFor('showAccount'));
        } catch (AuthException $e) {
            $this->flash->addMessage('error', $e->getMessage());
            $response = $response->withRedirect($this->router->pathFor("showAccount"));
        }
        return $response;
    }
}