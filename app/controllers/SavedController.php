<?php

class SavedController
{
    public function index()
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /?route=login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            // usuń pojedynczy
            if (isset($_POST['remove'])) {
                $file = $_POST['remove'];
                unset($_SESSION['saved'][$file]);
            }

            // wyczyść wszystko
            if (isset($_POST['clear_all'])) {
                unset($_SESSION['saved']);
            }
        }

        $saved = $_SESSION['saved'] ?? [];

        require __DIR__ . '/../views/saved.php';
    }
}