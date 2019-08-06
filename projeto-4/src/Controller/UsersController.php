<?php
namespace Code\Controller;

use Code\DB\Connection;
use Code\Entity\User;
use Code\Security\PasswordHash;
use Code\Security\Validator\Sanitizer;
use Code\Security\Validator\Validator;
use Code\Session\Flash;
use Code\View\View;

class UsersController
{

    public function index()
    {
        $view = new View('admin' . DIRECTORY_SEPARATOR . 'users' . DIRECTORY_SEPARATOR . 'index.phtml');
        $view->users = (new User(Connection::getInstance()))->findAll();
        return $view->render();
    }

    public function new()
    {
        try {
            if (filter_input(INPUT_SERVER, 'REQUEST_METHOD') == 'POST') {
                $data = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

                $data = Sanitizer::sanitizeData($data, User::$filters);

                if (!Validator::validateRequiredFields($data)) {
                    Flash::add('warning', 'Preencha todos os campos!');
                    return header('Location:' . HOME . DIRECTORY_SEPARATOR . 'users' . DIRECTORY_SEPARATOR . 'new');
                }

                if (!Validator::validatePasswordMinStringLenght($data['password'])) {
                    Flash::add('warning', 'Senha deve conter pelo menos 6 (seis) caracteres!');
                    return header('Location:' . HOME . DIRECTORY_SEPARATOR . 'users' . DIRECTORY_SEPARATOR . 'new');
                }

                if (!Validator::validatePasswordConfirm($data['password'], $data['password_confirm'])) {
                    Flash::add('warning', 'Senhas não conferem!');
                    return header('Location:' . HOME . DIRECTORY_SEPARATOR . 'users' . DIRECTORY_SEPARATOR . 'new');
                }

                $post = new User(Connection::getInstance());

                $data['password'] = PasswordHash::hash($data['password']);

                unset($data['password_confirm']);

                if (!$post->insert($data)) {
                    Flash::add('danger', 'Erro ao criar usuário!');
                    return header('Location:' . HOME . DIRECTORY_SEPARATOR . 'users' . DIRECTORY_SEPARATOR . 'new');
                }
                Flash::add('success', 'Usuário cadastrado com sucesso!');
                return header('Location:' . HOME . DIRECTORY_SEPARATOR . 'users');

            }

            $view = new View('admin' . DIRECTORY_SEPARATOR . 'users' . DIRECTORY_SEPARATOR . 'new.phtml');
            $view->users = (new User(Connection::getInstance()))->findAll('id, first_name, last_name');
            return $view->render();
        } catch (\Exception $e) {
            if (APP_DEBUG) {
                Flash::add('danger', $e->getMessage());
                return header('Location:' . HOME . DIRECTORY_SEPARATOR . 'users');
            }
            Flash::add('danger', 'Ocorreu um problema interno, por favor contacte o administrador.');
            return header('Location:' . HOME . DIRECTORY_SEPARATOR . 'users');
        }
    }

    public function edit($id = null)
    {
        try {
            if (filter_input(INPUT_SERVER, 'REQUEST_METHOD') == 'POST') {
                $data = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

                $data = Sanitizer::sanitizeData($data, User::$filters);

                $data['id'] = (int) $id;

                if (!Validator::validateRequiredFields($data)) {
                    Flash::add('warning', 'Preencha todos os campos!');
                    return header('Location:' . HOME . DIRECTORY_SEPARATOR . 'users' . DIRECTORY_SEPARATOR . 'edit' . DIRECTORY_SEPARATOR . $id);
                }


                if ($data['password']) {
                    if (!Validator::validatePasswordMinStringLenght($data['password'])) {
                        Flash::add('warning', 'Senha deve conter pelo menos 6 (seis) caracteres!');
                        return header('Location:' . HOME . DIRECTORY_SEPARATOR . 'users' . DIRECTORY_SEPARATOR . 'edit' . DIRECTORY_SEPARATOR . $id);
                    }

                    if (!Validator::validatePasswordConfirm($data['password'], $data['password_confirm'])) {
                        Flash::add('warning', 'Senhas não conferem!');
                        return header('Location:' . HOME . DIRECTORY_SEPARATOR . 'users' . DIRECTORY_SEPARATOR . 'edit' . DIRECTORY_SEPARATOR . $id);
                    }

                    $data['password'] = PasswordHash::hash($data['password']);

                } else {
                    unset($data['password']);
                }

                unset($data['password_confirm']);
                $post = new User(Connection::getInstance());

                if (!$post->update($data)) {
                    Flash::add('danger', 'Erro ao atualizar usuário!');
                    return header('Location:' . HOME . DIRECTORY_SEPARATOR . 'users' . DIRECTORY_SEPARATOR . 'edit' . DIRECTORY_SEPARATOR . $id);
                }
                Flash::add('success', 'Usuário atualizado com sucesso!');
                return header('Location:' . HOME . DIRECTORY_SEPARATOR . 'users');
            }

            $view = new View('admin' . DIRECTORY_SEPARATOR . 'users' . DIRECTORY_SEPARATOR . 'edit.phtml');
            $view->user = (new User(Connection::getInstance()))->find($id);

            return $view->render();

        } catch (\Exception $e) {
            if (APP_DEBUG) {
                Flash::add('danger', $e->getMessage());
                return header('Location:' . HOME . DIRECTORY_SEPARATOR . 'users');
            }
            Flash::add('danger', 'Ocorreu um problema interno, por favor contacte o administrador.');
            return header('Location:' . HOME . DIRECTORY_SEPARATOR . 'users');
        }
    }

    public function remove($id = null)
    {
        try {
            $post = new User(Connection::getInstance());

            if (!$post->delete($id)) {
                Flash::add('danger', 'Erro ao realizar remoção do usuário');
                return header('Location:' . HOME . DIRECTORY_SEPARATOR . 'users');
            }

            Flash::add('success', 'Usuário removido com sucesso!');
            return header('Location:' . HOME . DIRECTORY_SEPARATOR . 'users');
        } catch (\Exception $e) {
            if (APP_DEBUG) {
                Flash::add('danger', $e->getMessage());
                return header('Location:' . HOME . DIRECTORY_SEPARATOR . 'users');
            }
            Flash::add('danger', 'Ocorreu um problema interno, por favor contacte o administrador.');
            return header('Location:' . HOME . DIRECTORY_SEPARATOR . 'users');
        }

    }

}