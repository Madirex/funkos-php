<footer class="mt-4 text-center">
    <hr>
    <p>CRUD de Funkos - <a href="https://www.madirex.com/" target="_blank">Madirex</a></p>
    <p>&copy; <?php echo date('Y'); ?> Madirex. Todos los derechos reservados.</p>
    <?php
    if(isset($_SESSION['username'])) {
        echo "Visitas durante esta sesión: " . $_SESSION['visits'];
    }
    ?>
</footer>
