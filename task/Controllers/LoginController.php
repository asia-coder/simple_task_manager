<?php
/**
 * Created by PhpStorm.
 * User: dilshod
 * Date: 2019-05-12
 * Time: 14:43
 */

namespace Task\Controllers;

use Klein\ServiceProvider as Service;
use Klein\Request;
use Klein\Response;

class LoginController extends Controller
{
    protected static $ADMIN_LOGIN = "admin";
    protected static $ADMIN_PASSWORD = "123";

    public function index(Request $request, Response $response, Service $service)
    {
        if (isset($_SESSION['admin_name'])) {
            return $response->redirect('/');
        }

        $service->title = "Авторизация";
        $service->template = "login";
        $service->errors = $service->flashes('errors');
        $service->render($this->getTemplatePath());
    }

    public function login(Request $request, Response $response, Service $service)
    {
        if (isset($_SESSION['admin_name'])) {
            return $response->redirect('/');
        }

        $user_login = $service->escape($request->param('login'));
        $user_password = $request->param('password');

        $error = false;
        if (empty($user_login)) {
            $error = true;
            $service->flash('Запольните поле: Логин', 'errors');
        }

        if (empty($user_password)) {
            $error = true;
            $service->flash('Запольните поле: Пароль', 'errors');
        }

        if ($error) {
            return $service->back();
        }

        if ($user_login === static::$ADMIN_LOGIN && $user_password === self::$ADMIN_PASSWORD) {
            session_start();
            $_SESSION['admin_name'] = static::$ADMIN_LOGIN;
            setcookie('login', 'auth');
            return $response->redirect('/');
        } else {
            $service->flash('Логин или пароль введен не правильно.', 'errors');
        }

        return $service->back();
    }

    public function logout(Request $request, Response $response, Service $service)
    {
        unset($_SESSION['admin_name']);
        setcookie('login', null);
        $response->redirect('/');
    }
}