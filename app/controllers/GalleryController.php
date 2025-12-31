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

        if (!isset($_SESSION['saved_images'])) {
            $_SESSION['saved_images'] = [];
        }

        $error = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['image'])) {
            $service = new ImageService();
            $result = $service->upload($_FILES['image']);

            if ($result !== true) {
                $error = $result;
            }
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_selected'])) {

            $_SESSION['saved_images'] = [];

            if (!empty($_POST['images'])) {
                foreach ($_POST['images'] as $filename => $data) {
                    if (isset($data['checked'])) {
                        $_SESSION['saved_images'][$filename] = [
                            'qty' => max(1, (int)($data['qty'] ?? 1))
                        ];
                    }
                }
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

        $savedImages = $_SESSION['saved_images'];

        require __DIR__ . '/../views/gallery.php';
    }
}