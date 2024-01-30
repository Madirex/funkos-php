<?php

namespace services;

use Exception;
use models\User;
use PDO;

require_once __DIR__ . '/../models/User.php';

/**
 * Class UsersService
 */
class UsersService
{
    private $db;

    /**
     * UsersService constructor.
     * @param PDO $db Conexión a la base de datos
     */
    public function __construct(PDO $db)
    {
        $this->db = $db;
    }
    
    /**
     * Autenticar usuario
     * @param $username String Nombre de usuario
     * @param $password String Contraseña del usuario
     * @return User Devuelve el usuario autenticado
     * @throws Exception Si el usuario o la contraseña no son válidos
     */
    public function authenticate($username, $password): User
    {
        $user = $this->findUserByUsername($username);
        if ($user && password_verify($password, $user->password)) {
            return $user;
        }
        throw new Exception('Usuario o contraseña no válidos');
    }

    /**
     * Busca un usuario por username
     * @param $username String Nombre de usuario
     * @return User|null Devuelve el usuario con el username indicado o null si no existe
     */
    public function findUserByUsername($username)
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $userRow = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$userRow) {
            return null;
        }

        $stmtRoles = $this->db->prepare("SELECT roles FROM user_roles WHERE user_id = :user_id");
        $stmtRoles->bindParam(':user_id', $userRow['id']);
        $stmtRoles->execute();
        $roles = $stmtRoles->fetchAll(PDO::FETCH_COLUMN);

        return new User(
            $userRow['id'],
            $userRow['username'],
            $userRow['password'],
            $userRow['name'],
            $userRow['surnames'],
            $userRow['email'],
            $userRow['created_at'],
            $userRow['updated_at'],
            $userRow['is_deleted'],
            $roles
        );
    }
}
