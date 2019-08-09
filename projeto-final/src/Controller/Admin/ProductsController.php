<?php
namespace Code\Controller\Admin;

use Ausi\SlugGenerator\SlugGenerator;
use Code\DB\Connection;
use Code\Entity\Product;
use Code\Security\Validator\Sanitizer;
use Code\Security\Validator\Validator;
use Code\Session\Flash;
use Code\View\View;

class ProductsController
{
    public function index()
    {
        $view = new View('admin' . DS . 'products' . DS . 'index.phtml');
        $view->products = (new Product(Connection::getInstance()))->findAll();

        return $view->render();
    }

    public function new()
    {

        if (filter_input(INPUT_SERVER, 'REQUEST_METHOD') === 'POST') {
            $data = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $data = Sanitizer::sanitizeData($data, Product::$filters);

            if(!Validator::validateRequiredFields($data)) {
                Flash::add('warning', 'Preencha todos os campos!');
                return header('Location: ' . HOME . '/admin/products/new');
            }

            $data['slug'] = (new SlugGenerator())->generate($data['name']);
            $data['price'] = number_format($data['price'], 2, '.', '');

            $product = new Product(Connection::getInstance());

            if(!$product->insert($data)) {
                Flash::add('error', 'Erro ao criar produto!');
                return header('Location: ' . HOME . '/admin/products/new');
            }

            Flash::add('success', 'Produto criado com sucesso!');
            return header('Location: ' . HOME . '/admin/products');
        }
        return (new View('admin' . DS . 'products' . DS . 'new.phtml'))->render();
    }

    public function edit($id = null)
    {
        if (filter_input(INPUT_SERVER, 'REQUEST_METHOD') === 'POST') {
            $data = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            $data['id'] = (int) $id;
            $data['price'] = number_format($data['price'], 2, '.', '');

            $product = new Product(Connection::getInstance());

            if(!$product->update($data)) {
                Flash::add('error', 'Erro ao atualizar produto!');
                return header('Location: ' . HOME . '/admin/products/edit/' . $id);
            }

            Flash::add('success', 'Produto atualizado com sucesso!');
            return header('Location: ' . HOME . '/admin/products');
        }

        $view = (new View('admin' . DS . 'products' . DS . 'edit.phtml'));
        $view->product = (new Product(Connection::getInstance()))->find($id);

        return $view->render();
    }


}