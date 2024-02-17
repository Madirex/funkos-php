<?php

use config\Config;
use models\Funko;
use services\CategoriesService;
use services\FunkosService;
use services\SessionService;

require_once 'vendor/autoload.php';

require_once __DIR__ . '/services/SessionService.php';
require_once __DIR__ . '/config/Config.php';
require_once __DIR__ . '/services/FunkosService.php';
require_once __DIR__ . '/services/CategoriesService.php';
require_once __DIR__ . '/models/Funko.php';

$session = SessionService::getInstance();
if (!$session->isAdmin()) {
    header("Location: index.php?error=permission");
    exit;
}

$config = Config::getInstance();
$categoriesService = new CategoriesService($config->db);
$funkosService = new FunkosService($config->db);

$categories = $categoriesService->findAll();
$errors = [];
$funko = null;
$funkoId = -1;

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $funkoId = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
    if (!$funkoId) {
        echo "<div class='error-banner' id='errorBanner'>No se proporcionó un id de un Funko</div>";
        exit;
    }

    try {
        $funko = $funkosService->findById($funkoId);
    } catch (Exception $e) {
        $error = 'Error en el sistema. Por favor intente más tarde.';
        echo "<div class='error-banner' id='errorBanner'>$error</div>";
    }

    if (!$funko) {
        header('Location: index.php');
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $description = trim(filter_input(INPUT_POST, 'description', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
    $price = filter_input(INPUT_POST, 'price', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $stock = filter_input(INPUT_POST, 'stock', FILTER_SANITIZE_NUMBER_INT);
    $category = filter_input(INPUT_POST, 'category', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $funkoId = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
    $category = $categoriesService->findByName($category);

    // Validamos los datos
    if (empty($description)) {
        $errors['description'] = 'La descripción es obligatoria.';
    }

    if (!isset($price) || $price === '') {
        $errors['price'] = 'El precio es obligatorio.';
    } elseif ($price < 0) {
        $errors['price'] = 'El precio no puede ser negativo.';
    }

    if (!isset($stock) || $stock === '') {
        $errors['stock'] = 'El stock es obligatorio.';
    } elseif ($stock < 0) {
        $errors['stock'] = 'El stock no puede ser negativo.';
    }

    if (empty($category)) {
        $errors['category'] = 'La categoría es obligatoria.';
    }

    if (count($errors) === 0) {
        // Actualizamos el funko
        $funko = new Funko();
        $funko->description = $description;
        $funko->price = $price;
        $funko->stock = $stock;
        $funko->id = $funkoId;
        $funko->categoryId = $category->id;

        try {
            $funkosService->update($funko ,false);
            header("Location: index.php?updated=true");
            exit();
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
    <title>Actualizar Funko</title>
    <?php include 'head_styles.php'; ?>
</head>
<body>
    <?php require_once 'header.php'; ?>
<div class="container">
    <h1>Actualizar Funko</h1>

    <form action="update.php" method="post">

        <input type="hidden" name="id" value="<?php echo $funkoId; ?>">
        <div class="form-group">
            <label for="description">Descripción:</label>
            <textarea class="form-control" id="description" name="description"
                      required><?php echo htmlspecialchars_decode($funko->description); ?></textarea>
            <?php if (isset($errors['description'])): ?>
                <small class="text-danger"><?php echo $errors['description']; ?></small>
            <?php endif; ?>
        </div>
        <div class="form-group">
            <label for="price">Precio:</label>
            <input class="form-control" id="price" min="0.0" name="price" step="0.01" type="number" required
                   value="<?php echo htmlspecialchars_decode($funko->price); ?>">
            <?php if (isset($errors['price'])): ?>
                <small class="text-danger"><?php echo $errors['price']; ?></small>
            <?php endif; ?>
        </div>
        <div class="form-group">
            <label for="image">Imagen:</label>
            <input class="form-control" id="image" name="image" readonly type="text"
                   value="<?php echo htmlspecialchars_decode($funko->image); ?>">
        </div>
        <div class="form-group">
            <label for="stock">Stock:</label>
            <input class="form-control" id="stock" min="0" name="stock" type="number" required
                   value="<?php echo htmlspecialchars_decode($funko->stock); ?>">
            <?php if (isset($errors['stock'])): ?>
                <small class="text-danger"><?php echo $errors['stock']; ?></small>
            <?php endif; ?>
        </div>
        <div class="form-group">
            <label for="category">Categoría:</label>
            <select class="form-control" id="category" name="category" required>
                <option value="">Seleccione una categoría</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?php echo htmlspecialchars_decode($cat->name); ?>" <?php if ($cat->name == $funko->categoryName) echo 'selected'; ?>>
                        <?php echo htmlspecialchars_decode($cat->name); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <?php if (isset($errors['category'])): ?>
                <small class="text-danger"><?php echo $errors['category']; ?></small>
            <?php endif; ?>
        </div>

        <button class="btn btn-primary" type="submit">Actualizar</button>
        <a class="btn btn-secondary mx-2" href="index.php">Volver</a>
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


