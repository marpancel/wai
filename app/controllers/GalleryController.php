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

        
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['upload'])) {

            $title = trim($_POST['title'] ?? '');

            if ($title === '') {
                $error = 'Tytuł zdjęcia jest wymagany';
            } elseif (empty($_FILES['image']['name'])) {
                $error = 'Plik zdjęcia jest wymagany';
            } else {
                try {
                    $service = new ImageService();
                    $result = $service->upload(
                        $_FILES['image'],
                        $_SESSION['user_id'], // STRING
                        $title
                    );

                    if ($result !== true) {
                        $error = $result;
                    }
                } catch (Throwable $e) {
                    $error = 'Błąd serwera podczas zapisu zdjęcia';
                }
            }
        }

        
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_selected'])) {

            $_SESSION['saved_images'] = [];

            foreach ($_POST['images'] ?? [] as $filename => $data) {
                if (isset($data['checked'])) {
                    $_SESSION['saved_images'][$filename] = [
                        'qty' => max(1, (int)($data['qty'] ?? 1))
                    ];
                }
            }
        }

        
        $perPage = 6;
        $page    = max(1, (int)($_GET['page'] ?? 1));
        $skip    = ($page - 1) * $perPage;

        
        $total  = $mongo->countImages();
        $pages  = max(1, ceil($total / $perPage));

        $files  = $mongo->getImagesWithAuthors($perPage, $skip);

        $savedImages = $_SESSION['saved_images'];

        require __DIR__ . '/../views/gallery.php';
    }
}