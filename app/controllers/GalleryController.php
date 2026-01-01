<?php

require_once __DIR__ . '/../services/ImageService.php';
require_once __DIR__ . '/../services/MongoService.php';

class GalleryController
{
    public function index(): void
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /?route=login');
            exit;
        }

        $mongo = new MongoService();
        $user  = $mongo->getUserById($_SESSION['user_id']);

        $_SESSION['saved_images'] ??= [];

        $error = null;

        /* UPLOAD */
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['upload'])) {
            $service = new ImageService();
            $result = $service->upload(
                $_FILES['image'],
                $user,
                $_POST['title'] ?? ''
            );

            if ($result !== true) {
                $error = $result;
            }
        }

        /* SAVE (CAT 2C) */
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_selected'])) {
            $_SESSION['saved_images'] = [];

            foreach ($_POST['images'] ?? [] as $id => $data) {
                if (isset($data['checked'])) {
                    $_SESSION['saved_images'][$id] = [
                        'qty' => max(1, (int)$data['qty'])
                    ];
                }
            }
        }

        $perPage = 6;
        $page    = max(1, (int)($_GET['page'] ?? 1));
        $skip    = ($page - 1) * $perPage;

        $total  = $mongo->countImages();
        $pages  = max(1, ceil($total / $perPage));
        $files  = $mongo->getImages($perPage, $skip);

        $savedImages = $_SESSION['saved_images'];

        require __DIR__ . '/../views/gallery.php';
    }
}