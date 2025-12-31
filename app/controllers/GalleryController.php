<?php

require_once __DIR__ . '/../services/ImageService.php';
require_once __DIR__ . '/../services/MongoService.php';

class GalleryController
{
    public function index()
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /?route=login');
            exit;
        }

        $mongo = new MongoService();
        $user = $mongo->getUserById($_SESSION['user_id']);

        if (!isset($_SESSION['saved'])) {
            $_SESSION['saved'] = [];
        }

        $error = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['image'])) {
            $service = new ImageService();
            $result = $service->upload($_FILES['image']);

            if ($result !== true) {
                $error = $result;
            }
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save'])) {
            foreach ($_POST['save'] as $filename => $qty) {
                $_SESSION['saved'][$filename] = max(1, (int)$qty);
            }
        }

        $perPage = 6;
        $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $offset = ($page - 1) * $perPage;

        $thumbsDir = __DIR__ . '/../../public/thumbs';
        $files = [];

        if (is_dir($thumbsDir)) {
            $files = array_values(array_filter(
                scandir($thumbsDir),
                fn($f) => preg_match('/\.(jpg|jpeg|png)$/i', $f)
            ));
            rsort($files);
        }

        $total = count($files);
        $pages = max(1, ceil($total / $perPage));
        $files = array_slice($files, $offset, $perPage);

        require __DIR__ . '/../views/gallery.php';
    }
}