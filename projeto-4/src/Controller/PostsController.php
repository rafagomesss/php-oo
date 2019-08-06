<?php
namespace Code\Controller;

use Code\Authenticator\CheckUserLogged;
use Code\DB\Connection;
use Code\Entity\Category;
use Code\Entity\Post;
use Code\Entity\User;
use Code\Security\Validator\Sanitizer;
use Code\Security\Validator\Validator;
use Code\Session\Flash;
use Code\View\View;

class PostsController
{
    use CheckUserLogged;

    public function __construct()
    {
        if (!$this->check()) {
            return header('Location: ' . HOME . '/auth/login');
        }
    }

    public function index()
    {
        $view = new View('admin' . DIRECTORY_SEPARATOR . 'posts' . DIRECTORY_SEPARATOR . 'index.phtml');
        $view->posts = (new Post(Connection::getInstance()))->findAll();
        return $view->render();
    }

    public function new()
    {
        try {
            if (filter_input(INPUT_SERVER, 'REQUEST_METHOD') == 'POST') {
                $data = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

                $data = Sanitizer::sanitizeData($data, Post::$filters);

                if (!Validator::validateRequiredFields($data)) {
                    Flash::add('warning', 'Preencha todos os campos!');
                    return header('Location:' . HOME . DIRECTORY_SEPARATOR . 'posts' . DIRECTORY_SEPARATOR . 'new');
                }

                $post = new Post(Connection::getInstance());

                if (!$post->insert($data)) {
                    Flash::add('danger', 'Erro ao criar conteúdo!');
                    return header('Location:' . HOME . DIRECTORY_SEPARATOR . 'posts' . DIRECTORY_SEPARATOR . 'new');
                }
                Flash::add('success', 'Postagem criada com sucesso!');
                return header('Location:' . HOME . DIRECTORY_SEPARATOR . 'posts');

            }

            $view = new View('admin' . DIRECTORY_SEPARATOR . 'posts' . DIRECTORY_SEPARATOR . 'new.phtml');
            $view->users = (new User(Connection::getInstance()))->findAll('id, first_name, last_name');
            $view->categories = (new Category(Connection::getInstance()))->findAll('id, name');
            return $view->render();
        } catch (\Exception $e) {
            if (APP_DEBUG) {
                Flash::add('danger', $e->getMessage());
                return header('Location:' . HOME . DIRECTORY_SEPARATOR . 'posts');
            }
            Flash::add('danger', 'Ocorreu um problema interno, por favor contacte o administrador.');
            return header('Location:' . HOME . DIRECTORY_SEPARATOR . 'posts');
        }
    }

    public function edit($id = null)
    {
        try {
            if (filter_input(INPUT_SERVER, 'REQUEST_METHOD') == 'POST') {
                $data = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

                $data = Sanitizer::sanitizeData($data, Post::$filters);

                $data['id'] = (int) $id;

                if (!Validator::validateRequiredFields($data)) {
                    Flash::add('warning', 'Preencha todos os campos!');
                    return header('Location:' . HOME . DIRECTORY_SEPARATOR . 'posts' . DIRECTORY_SEPARATOR . 'edit' . DIRECTORY_SEPARATOR . $id);
                }

                $post = new Post(Connection::getInstance());

                if (!$post->update($data)) {
                    Flash::add('danger', 'Erro ao atualizar postagem!');
                    return header('Location:' . HOME . DIRECTORY_SEPARATOR . 'posts' . DIRECTORY_SEPARATOR . $id);
                }
                Flash::add('success', 'Postagem atualizada com sucesso!');
                return header('Location:' . HOME . DIRECTORY_SEPARATOR . 'posts' . DIRECTORY_SEPARATOR . $id);
            }

            $view = new View('admin' . DIRECTORY_SEPARATOR . 'posts' . DIRECTORY_SEPARATOR . 'edit.phtml');
            $view->post = (new Post(Connection::getInstance()))->find($id);
            $view->users = (new User(Connection::getInstance()))->findAll('id, first_name, last_name');
            $view->categories = (new Category(Connection::getInstance()))->findAll('id, name');

            return $view->render();

        } catch (\Exception $e) {
            if (APP_DEBUG) {
                Flash::add('danger', $e->getMessage());
                return header('Location:' . HOME . DIRECTORY_SEPARATOR . 'posts');
            }
            Flash::add('danger', 'Ocorreu um problema interno, por favor contacte o administrador.');
            return header('Location:' . HOME . DIRECTORY_SEPARATOR . 'posts');
        }
    }

        public function remove($id = null)
        {
            try {
                $post = new Post(Connection::getInstance());

                if (!$post->delete($id)) {
                    Flash::add('danger', 'Erro ao realizar remoção da postagem');
                    return header('Location:' . HOME . DIRECTORY_SEPARATOR . 'posts');
                }

                Flash::add('success', 'Postagem removida com sucesso!');
                return header('Location:' . HOME . DIRECTORY_SEPARATOR . 'posts');
            } catch (\Exception $e) {
                if (APP_DEBUG) {
                    Flash::add('danger', $e->getMessage());
                    return header('Location:' . HOME . DIRECTORY_SEPARATOR . 'posts');
                }
                Flash::add('danger', 'Ocorreu um problema interno, por favor contacte o administrador.');
                return header('Location:' . HOME . DIRECTORY_SEPARATOR . 'posts');
            }

        }

    }