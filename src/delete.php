<?php

use config\Config;
use models\Funko;
use services\FunkosService;
use services\SessionService;

require_once 'vendor/autoload.php';

require_once __DIR__ . '/config/Config.php';
require_once __DIR__ . '/services/FunkosService.php';
require_once __DIR__ . '/models/Funko.php';
require_once __DIR__ . '/services/SessionService.php';

$session = SessionService::getInstance();
if (!$session->isAdmin()) {
    echo "<script type='text/javascript'>
            alert('No tienes permisos para eliminar un funko');
            window.location.href = 'index.php';
          </script>";
    exit;
}

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$funko = null;

if ($id === false) {
    header('Location: index.php');
    exit;
} else {
    $config = Config::getInstance();
    $funkosService = new FunkosService($config->db);
    $funko = $funkosService->findById($id);
    if ($funko) {
        if ($funko->image !== Funko::$IMAGE_DEFAULT) {
            $imageUrl = $funko->image;
            $basePath = $config->uploadPath;
            $imagePathInUrl = parse_url($imageUrl, PHP_URL_PATH);
            $imageFile = basename($imagePathInUrl);
            $imageFilePath = $basePath . $imageFile;

            if (file_exists($imageFilePath)) {
                unlink($imageFilePath);
            }
        }
        $funkosService->deleteById($id);
        echo "<script type='text/javascript'>
                alert('Funko eliminado correctamente');
                window.location.href = 'index.php';
                </script>";
    }
}
