<?php
class Controller {
    protected function view($name, $data = []) {
        extract($data);
        require __DIR__ . '/../views/' . $name . '.php';
    }
}
