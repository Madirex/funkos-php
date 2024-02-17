<?php

use config\Config;
use services\CategoriesService;
use services\SessionService;

require_once 'vendor/autoload.php';

require_once __DIR__ . '/services/SessionService.php';
require_once __DIR__ . '/config/Config.php';
require_once __DIR__ . '/services/CategoriesService.php';
$session = $sessionService = SessionService::getInstance();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Categories CRUD</title>
    <?php include 'head_styles.php'; ?>
</head>
<body>
    <?php require_once 'header.php'; ?>
<div class="container" style="margin-top: 40px; margin-bottom: 40px;">
<?php $config = Config::getInstance(); ?>
<?php require_once 'banner_config.php'; ?>

<div id="categoryList">
    <h1>Listado de categorías</h1>
    <form action="index_categories.php" class="mb-3" method="get">
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
            <th>Nombre</th>
            <th>Fecha de creación</th>
            <th>Fecha de actualización</th>
            <th>Acciones</th>
        </tr>
        </thead>
        <tbody>
        <?php
        // Obtener el término de búsqueda si existe
        $searchTerm = $_GET['search'] ?? null;
        $CategoriesService = new CategoriesService($config->db);
        $Categories = $CategoriesService->findAll($searchTerm);
        ?>
        <?php foreach ($Categories as $category): ?>
            <tr>
                <td><?php echo htmlspecialchars_decode($category->id); ?></td>
                <td><?php echo htmlspecialchars_decode($category->name); ?></td>
                <td><?php echo htmlspecialchars_decode($category->createdAt); ?></td>
                <td><?php echo htmlspecialchars_decode($category->updatedAt); ?></td>
                <td>
                    <a class="btn btn-primary btn-sm" style="min-width: 80px;"
                       href="details_categories.php?id=<?php echo $category->id; ?>">Detalles</a>
                    <a class="btn btn-secondary btn-sm" style="min-width: 80px;"
                       href="update_categories.php?id=<?php echo $category->id; ?>">Editar</a>
                       <?php $deleteLink = $session->isAdmin() ? "delete_categories.php?id={$category->id}&confirm=1" : "index_categories.php?error=permission"; ?>
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


                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <a class="btn btn-success" href="create_categories.php">Nueva categoría</a>
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