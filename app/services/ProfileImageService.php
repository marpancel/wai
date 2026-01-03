<?php

class ProfileImageService
{
    private string $dir;

    public function __construct()
    {
        $this->dir = __DIR__ . '/../../public/profiles';

        if (!is_dir($this->dir)) {
            mkdir($this->dir, 0755, true);
        }
    }

    public function process(array $file): ?string
    {
        // brak pliku
        if (!isset($file['tmp_name']) || $file['error'] !== UPLOAD_ERR_OK) {
            return null;
        }

        $info = getimagesize($file['tmp_name']);
        if (!$info) {
            return null;
        }

        if (!in_array($info['mime'], ['image/jpeg', 'image/png'])) {
            return null;
        }

        // źródło
        $src = $info['mime'] === 'image/jpeg'
            ? imagecreatefromjpeg($file['tmp_name'])
            : imagecreatefrompng($file['tmp_name']);

        if (!$src) {
            return null;
        }

        // miniatura 150x150
        $thumb = imagecreatetruecolor(150, 150);

        imagecopyresampled(
            $thumb,
            $src,
            0, 0, 0, 0,
            150, 150,
            imagesx($src),
            imagesy($src)
        );

        $filename = uniqid('profile_') . '.jpg';
        $path = $this->dir . '/' . $filename;

        imagejpeg($thumb, $path, 90);

        imagedestroy($src);
        imagedestroy($thumb);

        return $filename;
    }
}