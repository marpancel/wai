<?php

require_once __DIR__ . '/../services/MongoService.php';

class SavedController
{
    public function index(): void
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /?route=login');
            exit;
        }

        $_SESSION['saved_images'] ??= [];
        $savedImages = $_SESSION['saved_images'];

        $mongo = new MongoService();

        // 🔑 klucze sesji = ID z Mongo
        $files = $mongo->getImagesByIds(array_keys($savedImages));

        // usuwanie
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove'])) {
            foreach (array_keys($_POST['remove']) as $id) {
                unset($_SESSION['saved_images'][$id]);
            }

            header('Location: /?route=saved');
            exit;
        }

        require __DIR__ . '/../views/saved.php';
    }
}