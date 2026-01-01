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

        if (!isset($_SESSION['saved_images'])) {
            $_SESSION['saved_images'] = [];
        }

        $error = null;

        // =======================
        // UPLOAD ZDJĘCIA (CAT I)
        // =======================
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['upload'])) {
            $service = new ImageService();
            $result  = $service->upload($_FILES['image'], $_POST);

            if ($result !== true) {
                $error = $result;
            }
        }

        // =======================
        // ZAPAMIĘTYWANIE (CAT 2C)
        // =======================
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_selected'])) {

            // wymaganie z polecenia – nadpisujemy całość
            $_SESSION['saved_images'] = [];

            if (!empty($_POST['images'])) {
                foreach ($_POST['images'] as $filename => $data) {

                    if (isset($data['checked'])) {
                        $safeName = basename($filename);

                        $_SESSION['saved_images'][$safeName] = [
                            'qty' => max(1, (int)($data['qty'] ?? 1))
                        ];
                    }
                }
            }
        }

        // =======================
        // PAGINACJA + MONGODB
        // =======================
        $perPage = 6;
        $page    = max(1, (int)($_GET['page'] ?? 1));
        $skip    = ($page - 1) * $perPage;

        $total = $mongo->countImages();
        $pages = max(1, ceil($total / $perPage));

        $files = $mongo->getImages($perPage, $skip);
        $savedImages = $_SESSION['saved_images'];

        require __DIR__ . '/../views/gallery.php';
    }
}