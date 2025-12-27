<?php

require_once __DIR__ . '/../services/ImageService.php';

class GalleryController {

  public function upload() {
    $error = null;

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $service = new ImageService();
      $result = $service->upload($_FILES['image']);

      if ($result !== true) {
        $error = $result;
      }
    }

    require __DIR__ . '/../views/gallery.php';
  }
}