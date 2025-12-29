<?php

class ProfileImageService
{
    private string $dir;

    public function __construct()
    {
        $this->dir = realpath(__DIR__ . '/../../public/profiles/');
    }

    public function process($file): ?string
    {
        if ($file['error'] !== UPLOAD_ERR_OK) return null;

        $info = getimagesize($file['tmp_name']);
        if (!$info) return null;

        $mime = $info['mime'];
        if (!in_array($mime, ['image/jpeg', 'image/png'])) return null;

        $src = $mime === 'image/jpeg'
            ? imagecreatefromjpeg($file['tmp_name'])
            : imagecreatefrompng($file['tmp_name']);

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
        imagejpeg($thumb, $this->dir . '/' . $filename);

        return $filename;
    }
}