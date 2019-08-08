<?php
namespace Code\Controller\Admin;

use Code\DB\Connection;
use Code\Entity\Product;
use Code\View\View;

class ProductsController
{
    public function index()
    {
        $view = new View('admin' . DIRECTORY_SEPARATOR . 'products' . DIRECTORY_SEPARATOR . 'index.phtml');
        $view->products = (new Product(Connection::getInstance()))->findAll();

        return $view->render();
    }

    public function new()
    {
        return (new View('admin' . DIRECTORY_SEPARATOR . 'products' . DIRECTORY_SEPARATOR . 'new.phtml'))->render();
    }


}