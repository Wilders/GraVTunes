<?php

namespace app\helpers;

use app\models\User;
use Exception;

/**
 * Class Auth
 * @author Jules Sayer <jules.sayer@protonmail.com>
 * @package app\controllers
 */
class Auth {

    public static function attempt($login, $password) {
        try {
            $user = User::where('pseudo', '=', $login)->orWhere('email', '=', $login)->firstOrFail();
            if (!password_verify($password, $user->password)) throw new Exception();
            $_SESSION['user'] = $user;
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public static function user() {
        return self::check() ? $_SESSION['user'] : null;
    }

    public static function check() {
        return isset($_SESSION['user']);
    }

    public static function logout() {
        unset($_SESSION['user']);
    }

}