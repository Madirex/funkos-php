<?php

use config\Config;
use services\SessionService;
use services\UsersService;

require_once 'vendor/autoload.php';

require_once __DIR__ . '/services/SessionService.php';
require_once __DIR__ . '/services/UsersService.php';
require_once __DIR__ . '/config/Config.php';

$session = SessionService::getInstance();
$config = Config::getInstance();

$error = '';
$usersService = new UsersService($config->db);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $repeatPassword = filter_input(INPUT_POST, 'repeatPassword', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $surnames = filter_input(INPUT_POST, 'surnames', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    if (!$username || !$password) {
        $error = 'Usuario/a o contraseña inválidos.';
    } else if ($password !== $repeatPassword) {
        $error = 'Las contraseñas no coinciden.';
    } else if (!$name || !$surnames || !$email) {
        $error = 'Todos los campos son obligatorios.';
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'El email no es válido.';
    } else if ($usersService->findUserByUsername($username)) {
        $error = 'El usuario ya existe.';
    } else {
        try {
            $user = $usersService->createUser($username, $password, $repeatPassword, $name, $surnames, $email);
            if ($user) {
                $isAdmin = in_array('ADMIN', $user->roles);
                $session->login($user->username, $isAdmin);
                header('Location: index.php');
                exit;
            } else {
                $error = 'Usuario/a o contraseña inválidos.';
            }
        } catch (Exception $e) {
            $error = 'Error en el sistema. Por favor intente más tarde.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Registro</title>
    <?php include 'head_styles.php'; ?>
</head>
<body>
<?php require_once 'header.php'; ?>
<div class="container" style="width: 50%; margin-left: auto; margin-right: auto; margin-top: 40px; margin-bottom: 40px;">
    <h1>Registro</h1>
    <form action="register.php" method="post">
        <div class="form-group">
            <label for="username">Usuario:</label>
            <input class="form-control" id="username" name="username" required type="username" value="<?php echo isset($_POST['username']) ? htmlspecialchars_decode($_POST['username']) : ''; ?>">
            <label for="password">Contraseña:</label>
            <input class="form-control" id="password" name="password" required type="password" value="">
            <label for="repeatPassword">Repetir contraseña:</label>
            <input class="form-control" id="repeatPassword" name="repeatPassword" required type="password" value="">
            <label for="name">Nombre:</label>
            <input class="form-control" id="name" name="name" required type="name" value="<?php echo isset($_POST['name']) ? htmlspecialchars_decode($_POST['name']) : ''; ?>">
            <label for="surnames">Apellidos:</label>
            <input class="form-control" id="surnames" name="surnames" required type="surnames" value="<?php echo isset($_POST['surnames']) ? htmlspecialchars_decode($_POST['surnames']) : ''; ?>">
            <label for="email">Email:</label>
            <input class="form-control" id="email" name="email" required type="email" value="<?php echo isset($_POST['email']) ? htmlspecialchars_decode($_POST['email']) : ''; ?>">
        </div>
        <?php if ($error): ?>
            <p style="color: red;"><?php echo htmlspecialchars_decode($error); ?></p>
        <?php endif; ?>
        <button class="btn btn-primary" type="submit">Registrarse</button>
    </form>
</div>

<!-- Incluir el fragmento del footer -->
<?php
require_once 'footer.php';
?>

<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"
        integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj"
        crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct"
        crossorigin="anonymous"></script>
</body>
</html>