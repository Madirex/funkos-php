<?php
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
                if (banner) {
                    banner.style.animation = 'slideOut 0.5s ease-in';
                    setTimeout(function() {
                        banner.style.display = 'none';
                    }, 450);
                }
            }
            
            // Ocultar el banner después de 5 segundos
            setTimeout(function() {
                closeBanner();
            }, 5000); // 5000 milisegundos = 5 segundos
          </script>";
    ?>