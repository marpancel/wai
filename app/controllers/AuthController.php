<?php

require_once __DIR__ . '/../services/MongoService.php';
require_once __DIR__ . '/../services/ProfileImageService.php';
require_once __DIR__ . '/../models/User.php';

class AuthController
{
    public function register()
    {
        $error = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email']);
            $login = trim($_POST['login']);
            $pass1 = $_POST['password'];
            $pass2 = $_POST['password2'];

            if ($pass1 !== $pass2) {
                $error = 'Hasła nie są takie same';
            } else {
                $mongo = new MongoService();

                if ($mongo->findUserByLogin($login)) {
                    $error = 'Login jest już zajęty';
                } else {
                    $imgService = new ProfileImageService();
                    $photo = $imgService->process($_FILES['profile_photo']);

                    if (!$photo) {
                        $error = 'Błąd zdjęcia profilowego';
                    } else {
                        $hash = password_hash($pass1, PASSWORD_DEFAULT);

                        $user = new User(
                            $email,
                            $login,
                            $hash,
                            $photo
                        );

                        $mongo->saveUser($user->toArray());

                        header('Location: /?route=login');
                        exit;
                    }
                }
            }
        }

        require __DIR__ . '/../views/register.php';
    }
}