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

            $email = trim($_POST['email'] ?? '');
            $login = trim($_POST['login'] ?? '');
            $pass1 = $_POST['password'] ?? '';
            $pass2 = $_POST['password2'] ?? '';

            if (!$email || !$login || !$pass1 || !$pass2) {
                $error = 'Wszystkie pola są wymagane';
            } elseif ($pass1 !== $pass2) {
                $error = 'Hasła nie są takie same';
            } else {
                $mongo = new MongoService();

                if ($mongo->findUserByLogin($login)) {
                    $error = 'Login jest już zajęty';
                } else {
                    $imgService = new ProfileImageService();
                    $photo = $imgService->process($_FILES['profile_photo']);

                    if (!$photo) {
                        $error = 'Nie udało się zapisać zdjęcia profilowego';
                    } else {
                        $hash = password_hash($pass1, PASSWORD_DEFAULT);

                        $mongo->saveUser([
                            'email' => $email,
                            'login' => $login,
                            'password' => $hash,
                            'profile_photo' => $photo
                        ]);

                        header('Location: /?route=login');
                        exit;
                    }
                }
            }
        }

        require __DIR__ . '/../views/register.php';
    }

    public function login()
    {
        $error = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $login = trim($_POST['login']);
            $password = $_POST['password'];

            $mongo = new MongoService();
            $user = $mongo->findUserByLogin($login);

            if (!$user || !password_verify($password, $user['password'])) {
                $error = 'Nieprawidłowy login lub hasło';
            } else {
                $_SESSION['user_id'] = (string)$user['_id'];
                header('Location: /?route=gallery');
                exit;
            }
        }

        require __DIR__ . '/../views/login.php';
    }

    public function logout()
    {
        session_destroy();
        header('Location: /?route=login');
        exit;
    }
}