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
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Funkos CRUD</title>
    <?php include 'head_styles.php'; ?>
</head>
<body>
    <?php require_once 'header.php'; ?>
<div class="container" style="margin-top: 40px; margin-bottom: 40px;">

    <?php $config = Config::getInstance(); ?>
    <?php require_once 'banner_config.php'; ?>

<div id="funkoList">
    <h1>Listado de Funkos</h1>
    <form action="index.php" class="mb-3" method="get">
        <div class="input-group">
            <div class="input-group-append">
                <input class="form-control" name="search" placeholder="Buscar..." type="text">
                <button class="btn btn-primary" type="submit">Buscar</button>
            </div>
        </div>
    </form>

    <table class="table">
        <thead>
        <tr>
            <th>ID</th>
            <th>Descripción</th>
            <th>Categoría</th>
            <th>Precio</th>
            <th>Stock</th>
            <th>Imagen</th>
            <th>Acciones</th>
        </tr>
        </thead>
        <tbody>
        <?php
        // Obtener el término de búsqueda si existe
        $searchTerm = $_GET['search'] ?? null;
        $funkosService = new FunkosService($config->db);
        $funkos = $funkosService->findAllWithCategoryName($searchTerm);
        ?>
        <?php foreach ($funkos as $funko): ?>
            <tr>
                <td><?php echo htmlspecialchars_decode($funko->id); ?></td>
                <td><?php echo htmlspecialchars_decode($funko->description); ?></td>
                <td><?php echo htmlspecialchars_decode($funko->categoryName); ?></td>
                <td><?php echo htmlspecialchars_decode($funko->price); ?></td>
                <td><?php echo htmlspecialchars_decode($funko->stock); ?></td>
                <td>
                    <img alt="Imagen del funko" height="50"
                         src="<?php echo htmlspecialchars_decode($funko->image); ?>" width="50">
                </td>
                <td>
                    <a class="btn btn-primary btn-sm" style="min-width: 80px;"
                       href="details.php?id=<?php echo $funko->id; ?>">Detalles</a>

                    <!-- Solo los administradores pueden editar y eliminar -->
                    <?php if ($session->isAdmin()): ?>
                    <a class="btn btn-secondary btn-sm" style="min-width: 80px;"
                       href="update.php?id=<?php echo $funko->id; ?>">Editar</a>
                    <a class="btn btn-info btn-sm" style="min-width: 80px;"
                       href="update-image.php?id=<?php echo $funko->id; ?>">Imagen</a>
                       <?php $deleteLink = $session->isAdmin() ? "delete.php?id={$funko->id}&confirm=1" : "index.php?error=permission"; ?>
                       <a class="btn btn-danger btn-sm delete-btn" style="min-width: 80px;" data-toggle="modal" data-target="#confirmDeleteModal" 
                       data-delete-link="<?php echo $deleteLink; ?>">Eliminar</a>

                       <!-- Lógica de eliminación -->
                       <script>
                        document.addEventListener("DOMContentLoaded", function() {
                            var deleteButtons = document.querySelectorAll('.delete-btn');

                            deleteButtons.forEach(function(button) {
                            button.addEventListener('click', function() {
                                var deleteLink = button.getAttribute('data-delete-link');
                                var modal = document.getElementById('confirmDeleteModal');
                                var confirmButton = modal.querySelector('.btn-danger');

                                confirmButton.setAttribute('href', deleteLink);
                            });
                            });
                        });
                        </script>

                    <?php endif; ?>
                </td>

            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <?php if ($session->isAdmin()): ?>
    <a class="btn btn-success" href="create.php">Nuevo Funko</a>
    <?php endif; ?>
 </div>

    <p class="mt-4 text-center" style="font-size: smaller;">
        <?php
        if ($session->isLoggedIn()) {
            echo "<span>Nº de visitas: {$session->getVisitCount()}</span>";
            echo "<span>, desde el último login en: {$session->getLastLoginDate()}</span>";
        }
        ?>
    </p>

</div>

<?php require_once 'footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"
        integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj"
        crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct"
        crossorigin="anonymous"></script>

<!-- Modal de Confirmación de Eliminación -->
<?php require_once 'delete_confirm.php'; ?>

</body>
</html>