<?php
namespace Code\View;

class View
{
    private $view;

    public function __construct($view)
    {
        $this->view = $view;
        ob_start();
        require VIEWS_INCLUDES_PATH . 'header.phtml';
    }

    public function __set($index, $value)
    {
        $this->{$index} = $value;
    }

    public function __get($index)
    {
        return $this->{$index};
    }

    public function render()
    {
        require VIEWS_PATH . $this->view;
        require VIEWS_INCLUDES_PATH . 'footer.phtml';
        return ob_get_clean();
    }
}