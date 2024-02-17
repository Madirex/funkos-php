<?php

use config\Config;
use services\CategoriesService;
use services\SessionService;

require_once 'vendor/autoload.php';

require_once __DIR__ . '/services/SessionService.php';
require_once __DIR__ . '/config/Config.php';
require_once __DIR__ . '/services/CategoriesService.php';
require_once __DIR__ . '/models/Category.php';

$session = $sessionService = SessionService::getInstance();

$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$category = null;

if ($id === false) {
    header('Location: index_categories.php');
    exit;
} else {
    $config = Config::getInstance();
    $categoriesService = new CategoriesService($config->db);
    $category = $categoriesService->findById($id);
    if ($category === null) {
        header('Location: index_categories.php');
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Detalles de la categoría</title>
    <?php include 'head_styles.php'; ?>
</head>
<body>
    <?php require_once 'header.php'; ?>
<div class="container">

    <h1>Detalles de la categoría</h1>
    <dl class="row">
        <dt class="col-sm-2">ID:</dt>
        <dd class="col-sm-10"><?php echo htmlspecialchars_decode($category->id); ?></dd>
        <dt class="col-sm-2">Nombre:</dt>
        <dd class="col-sm-10"><?php echo htmlspecialchars_decode($category->name); ?></dd>
        <dt class="col-sm-2">Fecha de creación:</dt>
        <dd class="col-sm-10"><?php echo htmlspecialchars_decode($category->createdAt); ?></dd>
        <dt class="col-sm-2">Fecha de actualización:</dt>
        <dd class="col-sm-10"><?php echo htmlspecialchars_decode($category->updatedAt); ?></dd>
    </dl>
    <a class="btn btn-primary" href="index_categories.php">Volver</a>
</div>

<?php require_once 'footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"
        integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj"
        crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct"
        crossorigin="anonymous"></script>
</body>
</html>
