<?php

use config\Config;
use services\FunkosService;

require_once 'vendor/autoload.php';

require_once __DIR__ . '/config/Config.php';
require_once __DIR__ . '/services/FunkosService.php';
require_once __DIR__ . '/models/Funko.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $config = Config::getInstance();
        $id = $_POST['id'];
        $uploadDir = $config->uploadPath;
        $file = $_FILES['image'];
        $name = $file['name'];
        $type = $file['type'];
        $tmpPath = $file['tmp_name'];
        $error = $file['error'];
        $allowedTypes = ['image/jpeg', 'image/png'];
        $allowedExtensions = ['jpg', 'jpeg', 'png'];
        $fileInfo = finfo_open(FILEINFO_MIME_TYPE);
        $detectedType = finfo_file($fileInfo, $tmpPath);
        $extension = strtolower(pathinfo($name, PATHINFO_EXTENSION));

        if (in_array($detectedType, $allowedTypes) && in_array($extension, $allowedExtensions)) {
            $funkosService = new FunkosService($config->db);
            $funko = $funkosService->findById($id);
            if ($funko === null) {
                header('Location: index.php');
                exit;
            }
            $newName = $funko->uuid . '.' . $extension;
            move_uploaded_file($tmpPath, $uploadDir . $newName);
            $funko->image = $config->uploadUrl . $newName;
            $funkosService->update($funko, true);

            header('Location: update-image.php?id=' . $id);
            exit;
        }
        header('Location: index.php');
        exit;
    }
} else {
    header('Location: index.php');
    exit;
}
