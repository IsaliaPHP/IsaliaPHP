<?php

/**
 * Controlador de login
 * @author nelson rojas
 * @property string username
 */
class LoginController extends Controller
{
    public function index()
    {
        if (Auth::isLogged()) {
            $this->redirect('');
        }
        if (Request::hasPost('username') && Request::hasPost('password')) {
            $username = Request::post('username');
            $password = Request::post('password');

            $auth = new Auth("User", "check");

            if ($auth->login($username, $password)) {
                Flash::valid('Bienvenido ' . $username);
                $this->redirect('');
            } else {
                $this->username = $username;
                Flash::error('Usuario o contraseña incorrectos');
            }
        }
    }

    public function destroy()
    {
        Auth::logout();
        Flash::valid('Sesión cerrada correctamente');
        $this->redirect('');
    }
}
