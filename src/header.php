<?php

use services\SessionService;

require_once __DIR__ . '/services/SessionService.php';
$session = SessionService::getInstance();
$username = $session->isLoggedIn() ? $session->getUserName() : 'Invitado';
?>

<header>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <a class="navbar-brand" href="index.php">
            <img alt="Logo" class="d-inline-block align-text-top" height="30" src="/images/funkos.bmp" width="30">
            Funkos Madirex
        </a>
        <button aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation" class="navbar-toggler"
                data-target="#navbarNav" data-toggle="collapse" type="button">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="index.php">Funkos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="create.php">Nuevo Funko</a>
                </li>
            </ul>
            <ul class="navbar-nav ml-auto" style="flex-direction: column;">
            <li class="nav-item">
                    <?php
                        if (!$session->isLoggedIn()) {
                            echo '<li class="nav-item">
                            <a class="nav-link" href="register.php">Registro</a>
                            </li>';
                        }else{
                            echo '<div class="nav-username">';
                            echo htmlspecialchars($username, ENT_QUOTES, 'UTF-8');
                            echo '</div>';
                        }
                    ?>
                </li>
                <li class="nav-item">
                    <a class="nav-link"
                        <?php
                        if ($session->isLoggedIn()) {
                            echo 'href="logout.php">Logout';
                        } else {
                            echo 'href="login.php">Login';
                        }
                        ?>
                    </a>
                </li>
            </ul>
        </div>
    </nav>
</header>
