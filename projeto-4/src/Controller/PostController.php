<?php
namespace Code\Controller;

use Code\DB\Connection;
use Code\Entity\Post;
use Code\View\View;

class PostController
{
    public function index($slug)
    {
        $post = new Post(Connection::getInstance());

        $view = new View('site' . DIRECTORY_SEPARATOR . 'single.phtml');
        $view->post = current($post->where(['slug' => $slug]));

        return $view->render();
    }
}