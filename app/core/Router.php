<?php

class Router
{
    public function dispatch()
    {
        $route = $_GET['route'] ?? 'gallery';

        $controllerFile = __DIR__ . '/../controllers/' . ucfirst($route) . 'Controller.php';

        if (!file_exists($controllerFile)) {
            http_response_code(404);
            echo 'Controller not found';
            return;
        }

        require_once $controllerFile;

        $class = ucfirst($route) . 'Controller';

        if (!class_exists($class)) {
            http_response_code(500);
            echo 'Controller class missing';
            return;
        }

        $controller = new $class();

        if (!method_exists($controller, 'index')) {
            http_response_code(500);
            echo 'Index method missing';
            return;
        }

        $controller->index();
    }
}