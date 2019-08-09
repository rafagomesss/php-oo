<?php
namespace Code\Controller\Admin;

use Code\DB\Connection;
use Code\Entity\Product;
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
        return (new View('admin' . DS . 'products' . DS . 'new.phtml'))->render();
    }


}