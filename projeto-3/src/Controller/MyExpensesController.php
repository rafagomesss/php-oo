<?php
namespace Code\Controller;

use Code\View\View;

class MyExpensesController
{
    public function index()
    {

    }

    public function new()
    {
        $view = new View('expenses' . DIRECTORY_SEPARATOR . 'new.phtml');
        return $view->render();
    }
}
