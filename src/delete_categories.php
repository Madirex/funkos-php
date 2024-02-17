<?php

use config\Config;
use models\Category;
use services\CategoriesService;
use services\SessionService;

require_once 'vendor/autoload.php';

require_once __DIR__ . '/config/Config.php';
require_once __DIR__ . '/services/CategoriesService.php';
require_once __DIR__ . '/models/Category.php';
require_once __DIR__ . '/services/SessionService.php';

$session = SessionService::getInstance();
if (!$session->isAdmin()) {
    header("Location: index_categories.php?error=permission");
    exit;
}

$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$category = null;

if ($id === false) {
    header('Location: index_categories.php');
    exit;
} else {
    $config = Config::getInstance();
    $categoriesService = new CategoriesService($config->db);
    $category = $categoriesService->findById($id);
    if ($category) {
        $categoriesService->deleteById($id);
        header("Location: index_categories.php?removed=true");
    }
}
