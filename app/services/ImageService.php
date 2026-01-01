<?php

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/MongoService.php';

class ImageService
{
    private string $projectRoot;
    private string $uploadDir;
    private string $thumbDir;

    private array $allowedMime = ['image/jpeg', 'image/png'];
    private int $maxSize = 1_000_000; // 1 MB – ZGODNIE Z WYTYCZNYMI

    public function __construct()
    {
        $this->projectRoot = realpath(__DIR__ . '/../../');
        $this->uploadDir = $this->projectRoot . '/public/images/';
        $this->thumbDir  = $this->projectRoot . '/public/thumbs/';

        if (!is_dir($this->uploadDir) || !is_writable($this->uploadDir)) {
            throw new RuntimeException('Katalog images nie istnieje lub brak uprawnień');
        }

        if (!is_dir($this->thumbDir) || !is_writable($this->thumbDir)) {
            throw new RuntimeException('Katalog thumbs nie istnieje lub brak uprawnień');
        }
    }

    public function upload(array $file, array $post)
    {
        if ($file['error'] !== UPLOAD_ERR_OK) {
            return 'Błąd uploadu pliku';
        }

        $errors = [];

        if ($file['size'] > $this->maxSize) {
            $errors[] = 'Plik jest za duży (max 1 MB)';
        }

        $info = @getimagesize($file['tmp_name']);
        if (!$info || !in_array($info['mime'], $this->allowedMime, true)) {
            $errors[] = 'Nieprawidłowy format pliku (dozwolone: JPG, PNG)';
        }

        $title  = trim($post['title']  ?? '');
        $author = trim($post['author'] ?? '');

        if ($title === '' || $author === '') {
            $errors[] = 'Tytuł i autor są wymagane';
        }

        if (!empty($errors)) {
            return implode(' | ', $errors);
        }

        $ext = $info['mime'] === 'image/png' ? 'png' : 'jpg';
        $filename = uniqid('img_', true) . '.' . $ext;

        if (!move_uploaded_file($file['tmp_name'], $this->uploadDir . $filename)) {
            return 'Nie udało się zapisać pliku na serwerze';
        }

        $this->createThumbnail(
            $this->uploadDir . $filename,
            $this->thumbDir . $filename
        );

        $mongo = new MongoService();
        $mongo->saveImage([
            'filename'    => $filename,
            'title'       => $title,
            'author'      => $author,
            'uploaded_at' => new MongoDB\BSON\UTCDateTime(),
            'public'      => true
        ]);

        return true;
    }

    private function createThumbnail(string $srcPath, string $destPath): void
    {
        $info = getimagesize($srcPath);
        [$srcW, $srcH] = $info;

        $src = $info['mime'] === 'image/png'
            ? imagecreatefrompng($srcPath)
            : imagecreatefromjpeg($srcPath);

        $thumbW = 200;
        $thumbH = 125;

        $thumb = imagecreatetruecolor($thumbW, $thumbH);

        imagecopyresampled(
            $thumb,
            $src,
            0, 0, 0, 0,
            $thumbW, $thumbH,
            $srcW, $srcH
        );

        $info['mime'] === 'image/png'
            ? imagepng($thumb, $destPath)
            : imagejpeg($thumb, $destPath, 85);

        imagedestroy($src);
        imagedestroy($thumb);
    }
}