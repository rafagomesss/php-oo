<?php
namespace Code\Controller;

use Code\View\View;

class ContactController
{
    public function index()
    {
        $view = new View('site' . DIRECTORY_SEPARATOR . 'contact.phtml');

        return $view->render();
    }
}