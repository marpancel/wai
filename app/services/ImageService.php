<?php

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/MongoService.php';

class ImageService
{
    private string $projectRoot;
    private string $uploadDir;
    private string $thumbDir;

    private array $allowedTypes = ['image/jpeg', 'image/png'];
    private int $maxSize = 2_000_000; // 2 MB

    public function __construct()
    {
        $this->projectRoot = realpath(__DIR__ . '/../../');
        $this->uploadDir = $this->projectRoot . '/public/images/';
        $this->thumbDir  = $this->projectRoot . '/public/thumbs/';
    }

    public function upload(array $file)
    {

        if ($file['error'] !== UPLOAD_ERR_OK) {
            return 'Błąd uploadu';
        }

        if (!in_array($file['type'], $this->allowedTypes, true)) {
            return 'Nieprawidłowy typ pliku (dozwolone: JPG, PNG)';
        }

        if ($file['size'] > $this->maxSize) {
            return 'Plik jest za duży (max 2 MB)';
        }

        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $filename = uniqid('img_', true) . '.' . $ext;

        if (!move_uploaded_file($file['tmp_name'], $this->uploadDir . $filename)) {
            return 'Nie udało się zapisać pliku';
        }

        $this->createThumbnail(
            $this->uploadDir . $filename,
            $this->thumbDir . $filename
        );

        $mongo = new MongoService();
        $mongo->saveImage([
            'filename'     => $filename,
            'uploaded_at'  => new MongoDB\BSON\UTCDateTime(),
            'public'       => true
        ]);

        return true;
    }

    private function createThumbnail(string $srcPath, string $destPath): void
    {
        $info = @getimagesize($srcPath);
        if (!$info) {
            return;
        }

        [$srcWidth, $srcHeight] = $info;
        $mime = $info['mime'];

        if ($mime === 'image/jpeg') {
            $src = @imagecreatefromjpeg($srcPath);
        } elseif ($mime === 'image/png') {
            $src = @imagecreatefrompng($srcPath);
        } else {
            return;
        }

        if (!$src) {
            return;
        }

        $thumbWidth  = 200;
        $thumbHeight = 125;

        $thumb = imagecreatetruecolor($thumbWidth, $thumbHeight);

        imagecopyresampled(
            $thumb,
            $src,
            0, 0, 0, 0,
            $thumbWidth,
            $thumbHeight,
            $srcWidth,
            $srcHeight
        );

        if ($mime === 'image/jpeg') {
            imagejpeg($thumb, $destPath, 85);
        } else {
            imagepng($thumb, $destPath);
        }

        imagedestroy($src);
        imagedestroy($thumb);
    }
}