<?php
namespace Code\Controller\Admin;

use Ausi\SlugGenerator\SlugGenerator;
use Code\DB\Connection;
use Code\Entity\Product;
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

            $data['slug'] = (new SlugGenerator())->generate($data['name']);

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


}