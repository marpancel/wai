<?php

class Router
{
    public function dispatch()
    {
        $route = $_GET['route'] ?? 'gallery';

        switch ($route) {

            case 'gallery':
                require_once __DIR__ . '/../controllers/GalleryController.php';
                (new GalleryController())->index();
                break;

            case 'register':
                require_once __DIR__ . '/../controllers/AuthController.php';
                (new AuthController())->register();
                break;

            case 'login':
                require_once __DIR__ . '/../controllers/AuthController.php';
                (new AuthController())->login();
                break;

            case 'logout':
                require_once __DIR__ . '/../controllers/AuthController.php';
                (new AuthController())->logout();
                break;
            case 'saved':
                require_once __DIR__ . '/../controllers/SavedController.php';
                (new SavedController())->index();
                break;

            default:
                http_response_code(404);
                echo '404 – Nie znaleziono strony';
        }
    }
}