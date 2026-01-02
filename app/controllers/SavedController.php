<?php

class SavedController
{
    public function index(): void
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /?route=login');
            exit;
        }

        if (!isset($_SESSION['saved_images'])) {
            $_SESSION['saved_images'] = [];
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove'])) {

            foreach ($_POST['remove'] as $filename => $v) {

                $safe = basename($filename);

                if (isset($_SESSION['saved_images'][$safe])) {
                    unset($_SESSION['saved_images'][$safe]);
                }
            }

            header('Location: /?route=saved');
            exit;
        }

        $savedImages = $_SESSION['saved_images'];

        require __DIR__ . '/../views/saved.php';
    }
}