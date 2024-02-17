<?php

use config\Config;
use models\Category;
use services\CategoriesService;
use services\SessionService;

require_once 'vendor/autoload.php';

require_once __DIR__ . '/services/SessionService.php';
require_once __DIR__ . '/config/Config.php';
require_once __DIR__ . '/services/CategoriesService.php';
require_once __DIR__ . '/models/Category.php';

$session = SessionService::getInstance();
if (!$session->isAdmin()) {
    header("Location: index_categories.php?error=permission");
    exit;
}

$config = Config::getInstance();
$categoriesService = new CategoriesService($config->db);
$categories = $categoriesService->findAll();
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    $category = $categoriesService->findByName($name);

    if ($category) {
        $errors['category'] = 'La categoría ya existe.';
    }

    if (empty($name)) {
        $errors['name'] = 'El nombre es obligatorio.';
    }

    if (count($errors) === 0) {
        // Creamos el category
        $category = new Category();
        $category->name = $name;

        // Guardamos el category
        try {
            $categoriesService->save($category);
            header("Location: index_categories.php?created=true");
            exit;
        } catch (Exception $e) {
            // mostrar alert con excepción
            $error = 'Error en el sistema. Por favor intente más tarde.';
            echo "<div class='error-banner' id='errorBanner'>$error</div>";
        }
    }
}
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear categoría</title>
    <?php include 'head_styles.php'; ?>
</head>
<body>
    <?php require_once 'header.php'; ?>
<div class="container" style="margin-top: 40px; margin-bottom: 40px;">
    <h1>Crear categoría</h1>

    <form action="create_categories.php" method="post">
        <div class="form-group">
            <label for="name">Nombre:</label>
            <textarea class="form-control" id="name" name="name" required></textarea>
            <?php if (isset($errors['name'])): ?>
                <small class="text-danger"><?php echo $errors['name']; ?></small>
            <?php endif; ?>
        </div>
        <button class="btn btn-primary" type="submit">Crear</button>
        <a class="btn btn-secondary mx-2" href="index_categories.php">Volver</a>
    </form>
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