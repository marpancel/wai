<?php

class Router
{
    private array $routes = [
        'gallery'  => ['GalleryController', 'index'],
        'saved'    => ['SavedController', 'index'],
        'login'    => ['AuthController', 'login'],
        'register' => ['AuthController', 'register'],
        'logout'   => ['AuthController', 'logout'],
    ];

    public function dispatch(): void
    {
        $route = $_GET['route'] ?? 'gallery';

        if (!isset($this->routes[$route])) {
            http_response_code(404);
            echo '404 – Nie znaleziono strony';
            return;
        }

        [$controller, $method] = $this->routes[$route];

        $path = __DIR__ . '/../controllers/' . $controller . '.php';
        require_once $path;

        (new $controller())->$method();
    }
}