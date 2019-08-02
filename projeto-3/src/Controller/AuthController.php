<?php
namespace Code\Controller;

use Code\Authenticator\Authenticator;
use Code\DB\Connection;
use Code\Entity\User;
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
                die('Usuário ou senha incorretos!');
            }
            die('Usuário logado com sucesso!');
        }
        $view = new View('auth' . DIRECTORY_SEPARATOR . 'index.phtml');
        return $view->render();
    }

    public function logout()
    {
        $auth = (new Authenticator())->logout();
        die('Usuário deslogado!');
    }
}
