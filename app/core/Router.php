<?php
class Router {
    public function dispatch() {
        $page = $_GET['page'] ?? 'gallery';
        require __DIR__ . '/../controllers/' . ucfirst($page) . 'Controller.php';
        $class = ucfirst($page) . 'Controller';
        (new $class())->index();
    }
}
