<?php

use config\Config;
use services\FunkosService;
use services\SessionService;

require_once 'vendor/autoload.php';

require_once __DIR__ . '/services/SessionService.php';
require_once __DIR__ . '/config/Config.php';
require_once __DIR__ . '/services/FunkosService.php';
require_once __DIR__ . '/models/Funko.php';

$session = $sessionService = SessionService::getInstance();

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$funko = null;

if ($id === false) {
    header('Location: index.php');
    exit;
} else {
    $config = Config::getInstance();
    $funkosService = new FunkosService($config->db);
    $funko = $funkosService->findById($id);
    if ($funko === null) {
        header('Location: index.php');
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Detalles del Funko</title>
    <?php include 'head_styles.php'; ?>
</head>
<body>
    <?php require_once 'header.php'; ?>
<div class="container">

    <h1>Detalles del Funko</h1>
    <dl class="row">
        <dt class="col-sm-2">ID:</dt>
        <dd class="col-sm-10"><?php echo htmlspecialchars_decode($funko->id); ?></dd>
        <dt class="col-sm-2">Descripción:</dt>
        <dd class="col-sm-10"><?php echo htmlspecialchars_decode($funko->description); ?></dd>
        <dt class="col-sm-2">Precio:</dt>
        <dd class="col-sm-10"><?php echo htmlspecialchars_decode($funko->price); ?></dd>
        <dt class="col-sm-2">Imagen:</dt>
        <dd class="col-sm-10"><img alt="Funko Image" class="img-fluid"
                                   src="<?php echo htmlspecialchars_decode($funko->image); ?>"></dd>
        <dt class="col-sm-2">Stock:</dt>
        <dd class="col-sm-10"><?php echo htmlspecialchars_decode($funko->stock); ?></dd>
        <dt class="col-sm-2">Categoría:</dt>
        <dd class="col-sm-10"><?php echo htmlspecialchars_decode($funko->categoryName); ?></dd>
    </dl>
    <a class="btn btn-primary" href="index.php">Volver</a>
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
