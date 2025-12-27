<?php

class ImageService {

  private $allowedTypes = ['image/jpeg', 'image/png'];
  private $maxSize = 2_000_000;
  private $uploadDir = __DIR__ . '/../../public/images/';

  public function upload($file) {

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

    return true;
  }
}