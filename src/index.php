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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Funkos CRUD</title>
    <?php include 'head_styles.php'; ?>
</head>
<body>
    <?php require_once 'header.php'; ?>
<div class="container" style="margin-top: 40px; margin-bottom: 40px;">
    <?php
    echo "<h1>{$session->getWelcomeMessage()}</h1>";
    $config = Config::getInstance();

    // Mostrar el banner de error si el usuario no tiene permisos de administrador
    if (isset($_GET['error']) && $_GET['error'] === 'permission') {
        echo '<div class="error-banner" id="bannerInfo">
<span class="close-btn" onclick="closeBanner()">&times;</span>
        No tienes permisos para realizar esta acción
    </div>
    ';}

    // Mostrar el banner de creación
    else if (isset($_GET['created']) && $_GET['created'] === 'true') {
        echo '<div class="info-banner" id="bannerInfo">
<span class="close-btn" onclick="closeBanner()">&times;</span>
        Elemento creado correctamente
    </div>
    ';}

    // Mostrar el banner de actualización
    else if (isset($_GET['updated']) && $_GET['updated'] === 'true') {
        echo '<div class="info-banner" id="bannerInfo">
<span class="close-btn" onclick="closeBanner()">&times;</span>
        Elemento actualizado correctamente
    </div>
    ';}

    // Mostrar el banner de eliminación
    else if (isset($_GET['removed']) && $_GET['removed'] === 'true') {
        echo '<div class="info-banner" id="bannerInfo">
<span class="close-btn" onclick="closeBanner()">&times;</span>
        Elemento eliminado correctamente
    </div>
    ';}

    // Agregar JavaScript para cerrar el banner después de cierto tiempo y con animación
    echo "<script>
            function closeBanner() {
                var banner = document.getElementById('bannerInfo');
                banner.style.animation = 'slideOut 0.5s ease-in';
                setTimeout(function() {
                    banner.style.display = 'none';
                }, 450);
            }
            
            // Ocultar el banner después de 5 segundos
            setTimeout(function() {
                closeBanner();
            }, 5000); // 5000 milisegundos = 5 segundos
          </script>";
    ?>

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
                <td><?php echo htmlspecialchars_decode($funko->price); ?></td>
                <td><?php echo htmlspecialchars_decode($funko->stock); ?></td>
                <td>
                    <img alt="Imagen del funko" height="50"
                         src="<?php echo htmlspecialchars_decode($funko->image); ?>" width="50">
                </td>
                <td>
                    <a class="btn btn-primary btn-sm" style="min-width: 80px;"
                       href="details.php?id=<?php echo $funko->id; ?>">Detalles</a>
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


                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <a class="btn btn-success" href="create.php">Nuevo Funko</a>

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
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="confirmDeleteModalLabel">Confirmar Eliminación</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        ¿Estás seguro de que deseas eliminar este elemento?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
        <a id="deleteLink" href="#" class="btn btn-danger">Eliminar</a>
      </div>
    </div>
  </div>
</div>

</body>
</html>