<?php
namespace Code\Controller;

use Code\Authenticator\Authenticator;
use Code\DB\Connection;
use Code\Entity\User;
use Code\Session\Flash;
use Code\View\View;

class AuthController
{
    public function login()
    {
        if (filter_input(INPUT_SERVER, 'REQUEST_METHOD') == 'POST') {
            $data = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $user = new User(Connection::getInstance());
            $authenticator = new Authenticator($user);

            if (!$authenticator->login($data)) {
                Flash::add('warning', 'Usuário ou senha incorretos!');
                return header('Location: ' . HOME . '/auth/login');
            }
            Flash::add('success', 'Usuário logado com sucesso!');
            return header('Location: ' . HOME . '/myexpenses');
        }
        $view = new View('auth' . DIRECTORY_SEPARATOR . 'index.phtml');
        return $view->render();
    }

    public function logout()
    {
        $auth = (new Authenticator())->logout();
        Flash::add('success', 'Usuário deslogado!');
        return header('Location: ' . HOME . '/auth/login');
    }
}
