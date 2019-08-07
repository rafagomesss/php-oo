<?php
namespace Code\Controller;

use Ausi\SlugGenerator\SlugGenerator;
use Code\Authenticator\CheckUserLogged;
use Code\DB\Connection;
use Code\Entity\Category;
use Code\Security\Validator\Sanitizer;
use Code\Security\Validator\Validator;
use Code\Session\Flash;
use Code\View\View;

class CategoriesController
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
        $view = new View('admin' . DIRECTORY_SEPARATOR . 'categories' . DIRECTORY_SEPARATOR . 'index.phtml');
        $view->categories = (new Category(Connection::getInstance()))->findAll();
        return $view->render();
    }

    public function new()
    {
        try {
            if (filter_input(INPUT_SERVER, 'REQUEST_METHOD') == 'POST') {
                $data = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

                $data = Sanitizer::sanitizeData($data, Category::$filters);

                if (!Validator::validateRequiredFields($data)) {
                    Flash::add('warning', 'Preencha todos os campos!');
                    return header('Location:' . HOME . DIRECTORY_SEPARATOR . 'categories' . DIRECTORY_SEPARATOR . 'new');
                }

                $post = new Category(Connection::getInstance());

                $data['slug'] = (new SlugGenerator())->generate($data['name']);

                if (!$post->insert($data)) {
                    Flash::add('danger', 'Erro ao criar categoria!');
                    return header('Location:' . HOME . DIRECTORY_SEPARATOR . 'categories' . DIRECTORY_SEPARATOR . 'new');
                }
                Flash::add('success', 'Categoria cadastrada com sucesso!');
                return header('Location:' . HOME . DIRECTORY_SEPARATOR . 'categories');

            }
            $view = new View('admin' . DIRECTORY_SEPARATOR . 'categories' . DIRECTORY_SEPARATOR . 'new.phtml');
            return $view->render();
        } catch (\Exception $e) {
            if (APP_DEBUG) {
                Flash::add('danger', $e->getMessage());
                return header('Location:' . HOME . DIRECTORY_SEPARATOR . 'categories');
            }
            Flash::add('danger', 'Ocorreu um problema interno, por favor contacte o administrador.');
            return header('Location:' . HOME . DIRECTORY_SEPARATOR . 'categories');
        }
    }

    public function edit($id = null)
    {
        try {
            if (filter_input(INPUT_SERVER, 'REQUEST_METHOD') == 'POST') {
                $data = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

                $data = Sanitizer::sanitizeData($data, Category::$filters);

                $data['id'] = (int) $id;

                if (!Validator::validateRequiredFields($data)) {
                    Flash::add('warning', 'Preencha todos os campos!');
                    return header('Location:' . HOME . DIRECTORY_SEPARATOR . 'categories' . DIRECTORY_SEPARATOR . 'edit' . DIRECTORY_SEPARATOR . $id);
                }

                $post = new Category(Connection::getInstance());

                if (!$post->update($data)) {
                    Flash::add('danger', 'Erro ao atualizar categoria!');
                    return header('Location:' . HOME . DIRECTORY_SEPARATOR . 'categories' . DIRECTORY_SEPARATOR . 'edit' . DIRECTORY_SEPARATOR . $id);
                }
                Flash::add('success', 'Categoria atualizada com sucesso!');
                return header('Location:' . HOME . DIRECTORY_SEPARATOR . 'categories');
            }

            $view = new View('admin' . DIRECTORY_SEPARATOR . 'categories' . DIRECTORY_SEPARATOR . 'edit.phtml');
            $view->category = (new Category(Connection::getInstance()))->find($id);

            return $view->render();

        } catch (\Exception $e) {
            if (APP_DEBUG) {
                Flash::add('danger', $e->getMessage());
                return header('Location:' . HOME . DIRECTORY_SEPARATOR . 'categories');
            }
            Flash::add('danger', 'Ocorreu um problema interno, por favor contacte o administrador.');
            return header('Location:' . HOME . DIRECTORY_SEPARATOR . 'categories');
        }
    }

    public function remove($id = null)
    {
        try {
            $post = new Category(Connection::getInstance());

            if (!$post->delete($id)) {
                Flash::add('danger', 'Erro ao realizar remoção da categoria');
                return header('Location:' . HOME . DIRECTORY_SEPARATOR . 'categories');
            }

            Flash::add('success', 'Categoria removida com sucesso!');
            return header('Location:' . HOME . DIRECTORY_SEPARATOR . 'categories');
        } catch (\Exception $e) {
            if (APP_DEBUG) {
                Flash::add('danger', $e->getMessage());
                return header('Location:' . HOME . DIRECTORY_SEPARATOR . 'categories');
            }
            Flash::add('danger', 'Ocorreu um problema interno, por favor contacte o administrador.');
            return header('Location:' . HOME . DIRECTORY_SEPARATOR . 'categories');
        }

    }

}