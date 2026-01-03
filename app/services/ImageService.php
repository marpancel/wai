<?php

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/MongoService.php';

use MongoDB\Model\BSONDocument;
use MongoDB\BSON\UTCDateTime;

class ImageService
{
    private string $uploadDir;
    private string $thumbDir;

    private array $allowedMime = ['image/jpeg', 'image/png'];
    private int $maxSize = 1_000_000; // 1 MB

    public function __construct()
    {
        $root = realpath(__DIR__ . '/../../');

        $this->uploadDir = $root . '/public/images/';
        $this->thumbDir  = $root . '/public/thumbs/';
    }

    /**
     * @param array $file  $_FILES['image']
     * @param BSONDocument $user  dokument użytkownika z MongoDB
     * @param string $title
     */
    public function upload(array $file, BSONDocument $user, string $title): bool|string
    {
        if (trim($title) === '') {
            return 'Tytuł zdjęcia jest wymagany';
        }

        if (!isset($file['error']) || $file['error'] !== UPLOAD_ERR_OK) {
            return 'Błąd uploadu pliku';
        }

        if ($file['size'] > $this->maxSize) {
            return 'Plik jest za duży (max 1 MB)';
        }

        $info = getimagesize($file['tmp_name']);
        if (!$info || !in_array($info['mime'], $this->allowedMime, true)) {
            return 'Nieprawidłowy format (JPG / PNG)';
        }

        $ext = $info['mime'] === 'image/png' ? 'png' : 'jpg';
        $filename = uniqid('img_', true) . '.' . $ext;

        if (!move_uploaded_file($file['tmp_name'], $this->uploadDir . $filename)) {
            return 'Nie udało się zapisać pliku na serwerze';
        }

        $this->createThumbnail(
            $this->uploadDir . $filename,
            $this->thumbDir . $filename,
            $info['mime']
        );

        $mongo = new MongoService();
        $mongo->saveImage([
            'filename'             => $filename,
            'title'                => trim($title),
            'user_id'              => $user['_id'],
            'author_login'         => $user['login'],
            'author_profile_photo' => $user['profile_photo'] ?? null,
            'uploaded_at'          => new UTCDateTime(),
            'public'               => true,
        ]);

        return true;
    }

    private function createThumbnail(string $src, string $dest, string $mime): void
    {
        $srcImg = $mime === 'image/png'
            ? imagecreatefrompng($src)
            : imagecreatefromjpeg($src);

        $thumbW = 200;
        $thumbH = 125;

        $thumb = imagecreatetruecolor($thumbW, $thumbH);

        imagecopyresampled(
            $thumb,
            $srcImg,
            0, 0, 0, 0,
            $thumbW,
            $thumbH,
            imagesx($srcImg),
            imagesy($srcImg)
        );

        if ($mime === 'image/png') {
            imagepng($thumb, $dest);
        } else {
            imagejpeg($thumb, $dest, 85);
        }

        imagedestroy($srcImg);
        imagedestroy($thumb);
    }
}