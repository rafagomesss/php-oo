<?php
namespace Code\Controller;

use Code\DB\Connection;
use Code\Entity\Post;
use Code\Entity\User;
use Code\Session\Flash;
use Code\View\View;

class PostsController
{

    public function index()
    {
        $view = new View('admin' . DIRECTORY_SEPARATOR . 'posts' . DIRECTORY_SEPARATOR . 'index.phtml');
        $view->posts = (new Post(Connection::getInstance()))->findAll();
        return $view->render();
    }

    public function new()
    {
        if (filter_input(INPUT_SERVER, 'REQUEST_METHOD') == 'POST') {
            $data = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

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
        return $view->render();
    }

    public function edit($id = null)
    {
        if (filter_input(INPUT_SERVER, 'REQUEST_METHOD') == 'POST') {
            $data = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            $data['id'] = $id;

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

        return $view->render();
    }

}