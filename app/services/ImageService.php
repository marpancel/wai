<?php

class ImageService
{
    private string $projectRoot;
    private string $uploadDir;
    private string $thumbDir;

    private array $allowedTypes = ['image/jpeg', 'image/png'];
    private int $maxSize = 2_000_000;

    public function __construct()
    {
        $this->projectRoot = realpath(__DIR__ . '/../../');
        $this->uploadDir = $this->projectRoot . '/public/images/';
        $this->thumbDir  = $this->projectRoot . '/public/thumbs/';
    }

    public function upload($file)
    {
        if ($file['error'] !== UPLOAD_ERR_OK) {
            return 'Błąd uploadu';
        }

        if (!in_array($file['type'], $this->allowedTypes)) {
            return 'Nieprawidłowy typ pliku';
        }

        if ($file['size'] > $this->maxSize) {
            return 'Plik jest za duży';
        }

        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = uniqid() . '.' . $ext;

        if (!move_uploaded_file($file['tmp_name'], $this->uploadDir . $filename)) {
            return 'Nie udało się zapisać pliku';
        }

        $this->createThumbnail(
            $this->uploadDir . $filename,
            $this->thumbDir . $filename
        );

        return true;
    }

    private function createThumbnail($srcPath, $destPath)
    {
        $info = getimagesize($srcPath);
        if (!$info) return;

        [$srcWidth, $srcHeight] = $info;
        $mime = $info['mime'];

        if ($mime === 'image/jpeg') {
            $src = imagecreatefromjpeg($srcPath);
        } elseif ($mime === 'image/png') {
            $src = imagecreatefrompng($srcPath);
        } else {
            return;
        }

        $thumbWidth = 200;
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
            imagejpeg($thumb, $destPath);
        } else {
            imagepng($thumb, $destPath);
        }

        
    }
}