<?php
namespace Code\Controller;

use Code\DB\Connection;
use Code\Entity\Category;
use Code\Entity\Expense;
use Code\Entity\User;
use Code\View\View;

class MyExpensesController
{
    public function index()
    {
        $view = new View('expenses' . DIRECTORY_SEPARATOR . 'index.phtml');
        $view->expenses = (new Expense(Connection::getInstance()))->findAll();

        return $view->render();
    }

    public function new()
    {
        $method = filter_input(INPUT_SERVER, 'REQUEST_METHOD');
        $connection = Connection::getInstance();
        if ((string) $method === 'POST') {
            $data = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $expense = new Expense($connection);
            $expense->insert($data);

            return header('Location: ' . HOME . '/myexpenses');
        }
        $view = new View('expenses' . DIRECTORY_SEPARATOR . 'new.phtml');

        $view->categories = (new Category($connection))->findAll();
        $view->users = (new User($connection))->findAll();
        return $view->render();
    }

    public function edit($id)
    {
        $view = new View('expenses' . DIRECTORY_SEPARATOR . 'edit.phtml');
        $method = filter_input(INPUT_SERVER, 'REQUEST_METHOD');
        $connection = Connection::getInstance();

        if ((string) $method === 'POST') {
            $data = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            $data['id'] = $id;

            $expense = new Expense($connection);
            $expense->update($data);

            return header('Location: ' . HOME . '/myexpenses');
        }

        $view->categories = (new Category($connection))->findAll();
        $view->users = (new User($connection))->findAll();
        $view->expense = (new Expense($connection))->find($id);

        return $view->render();
    }

    public function remove($id)
    {
        $expense = new Expense(Connection::getInstance());
        $expense->delete($id);
        return header('Location: ' . HOME . '/myexpenses');
    }
}
