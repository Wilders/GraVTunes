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

    /**
     * @todo: Vérification du type de fichiers uploadés
     */
    public function updateProfile(Request $request, Response $response, array $args): Response {
        try {
            $description = filter_var($request->getParsedBodyParam('description'), FILTER_SANITIZE_STRING);
            $public = !is_null($request->getParsedBodyParam('public'));
            $files = $request->getUploadedFiles();

            if (mb_strlen($description, 'utf8') > 1000) throw new AuthException("Votre description ne doit pas dépasser 1000 caractères.");

            $user = Auth::user();

            $avatar = $files['avatar'];
            if ($avatar->getError() === UPLOAD_ERR_OK) {
                $avatarsPath = $this->uploadsPath . DIRECTORY_SEPARATOR . "avatars";
                $extension = pathinfo($avatar->getClientFilename(), PATHINFO_EXTENSION);
                $name = sprintf('%s.%0.8s', $user->id . '-' . bin2hex(random_bytes(8)), $extension);
                $avatar->moveTo($avatarsPath . DIRECTORY_SEPARATOR . $name);
                $user->avatar = $name;
            }

            $user->description = $description;
            $user->public = $public;
            $user->save();

            $this->flash->addMessage('success', "Les modifications apportées à votre compte ont été enregistrées !");
            $response = $response->withRedirect($this->router->pathFor('showAccount'));
        } catch (AuthException $e) {
            $this->flash->addMessage('error', $e->getMessage());
            $response = $response->withRedirect($this->router->pathFor("showAccount"));
        }
        return $response;
    }

    public function updateSecurity(Request $request, Response $response, array $args): Response {
        try {
            $actualPassword = filter_var($request->getParsedBodyParam('actual_password'), FILTER_SANITIZE_STRING);
            $newPassword = filter_var($request->getParsedBodyParam('newpassword'), FILTER_SANITIZE_STRING);
            $confNewPassword = filter_var($request->getParsedBodyParam('conf_newpassword'), FILTER_SANITIZE_STRING);

            if(!password_verify($actualPassword, Auth::user()->password)) throw new AuthException("Le mot de passe actuel n'est pas bon.");
            if (mb_strlen($newPassword, 'utf8') < 8) throw new AuthException("Votre nouveau mot de passe doit contenir au moins 8 caractères.");
            if ($newPassword != $confNewPassword) throw new AuthException("La confirmation du mot de passe n'est pas bonne.");

            $user = Auth::user();
            $user->password = password_hash($confNewPassword, PASSWORD_DEFAULT);
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