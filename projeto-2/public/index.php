<?php
require dirname(__DIR__) . DIRECTORY_SEPARATOR . 'bootstrap.php';

use Code\Controller\HomeController;
use Code\View\View;

$url = explode('/', trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/'));

$controller = isset($url[0]) && $url[0] ? $url[0] : 'home';
$action = isset($url[1]) && $url[1] ? $url[1] : 'index';
$param = isset($url[2]) && $url[2] ? $url[2] : [];

if (!class_exists($controller = "Code\Controller\\" . mb_convert_case($controller, MB_CASE_TITLE) . "Controller")) {
    print (new View('404.phtml'))->render();
    exit();
}

if (!method_exists($controller, $action)) {
    $action = 'index';
    $param = $url[1];
}

$response = call_user_func_array([new $controller, $action], [$param]);

print $response;


