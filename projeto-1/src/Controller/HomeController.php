<?php
namespace Code\Controller;

use Code\View\View;

class HomeController
{
    public function index()
    {
        $view = new View('site' . DIRECTORY_SEPARATOR . 'index.phtml');
        $view->name = 'Rafael Gomes';
        $view->product = [
            'id' => 2,
            'name' => 'SSD 248Gb',
            'price' => 245.90
        ];
        return $view->render();
    }
}