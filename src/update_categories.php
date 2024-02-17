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
$category = null;
$categoryId = -1;

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $categoryId = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    if (!$categoryId) {
        echo "<div class='error-banner' id='errorBanner'>No se proporcionó un id de un Category</div>";
        exit;
    }

    try {
        $category = $categoriesService->findById($categoryId);
    } catch (Exception $e) {
        $error = 'Error en el sistema. Por favor intente más tarde.';
        echo "<div class='error-banner' id='errorBanner'>$error</div>";
    }

    if (!$category) {
        header('Location: index_categories.php');
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim(filter_input(INPUT_POST, 'name', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
    $category = $categoriesService->findByName($name);
    $categoryId = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    // Validamos los datos
    if ($category) {
        $errors['category'] = 'La categoría ya existe.';
    }

    if (empty($name)) {
        $errors['name'] = 'El nombre es obligatorio.';
    }

    if (count($errors) === 0) {
        // Actualizamos el category
        $category = new Category();
        $category->id = $categoryId;
        $category->name = $name;

        try {
            $categoriesService->update($category);
            header("Location: index_categories.php?updated=true");
            exit;
        } catch (Exception $e) {
            $error = 'Error en el sistema. Por favor intente más tarde.';
            echo "<div class='error-banner' id='errorBanner'>$error</div>";
        }
    } else {
        echo "<div class='error-banner' id='errorBanner'>Error: " . implode(', ', $errors) . "</div>";
    }
}
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Actualizar categoría</title>
    <?php include 'head_styles.php'; ?>
</head>
<body>
    <?php require_once 'header.php'; ?>
<div class="container">
    <h1>Actualizar categoría</h1>

    <form action="update_categories.php" method="post">

        <input type="hidden" name="id" value="<?php echo $categoryId; ?>">
        <div class="form-group">
            <label for="name">Nombre:</label>
            <textarea class="form-control" id="name" name="name"
                      required><?php echo htmlspecialchars_decode($category->name); ?></textarea>
            <?php if (isset($errors['name'])): ?>
                <small class="text-danger"><?php echo $errors['name']; ?></small>
            <?php endif; ?>
        </div>
        <button class="btn btn-primary" type="submit">Actualizar</button>
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


