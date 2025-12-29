<?php

require_once __DIR__ . '/../services/ImageService.php';

class GalleryController
{
    public function index()
    {
        $error = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['image'])) {
            $service = new ImageService();
            $result = $service->upload($_FILES['image']);

            if ($result !== true) {
                $error = $result;
            }
        }

        $perPage = 6;
        $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $offset = ($page - 1) * $perPage;

        $thumbsDir = realpath(__DIR__ . '/../../public/thumbs');

        $files = array_values(array_filter(
            scandir($thumbsDir),
            fn($f) => preg_match('/\.(jpg|jpeg|png)$/i', $f)
        ));

        $total = count($files);
        $pages = ceil($total / $perPage);
        $files = array_slice($files, $offset, $perPage);

        require __DIR__ . '/../views/gallery.php';
    }
}