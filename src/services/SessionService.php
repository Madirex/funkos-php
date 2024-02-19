<?php

namespace services;

/**
 * Class SessionService
 * @package services
 * Esta clase se encarga de gestionar la sesión de usuario
 */
class SessionService
{
    private static $instance;
    private $expireAfterSeconds = 3600; // Una hora en segundos

    /**
     * SessionService constructor.
     */
    private function __construct()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $this->checkSessionValidity();
        $this->initSession();
    }

    /**
     * Devuelve la instancia de SessionService (Singleton)
     * @return SessionService Devuelve la instancia de SessionService
     */
    public static function getInstance(): SessionService
    {
        if (!isset(self::$instance)) {
            self::$instance = new SessionService();
        }
        return self::$instance;
    }

    /**
     * Comprueba si la sesión ha expirado
     */
    private function checkSessionValidity()
    {
        if (isset($_SESSION['last_activity'])) {
            $secondsInactive = time() - $_SESSION['last_activity'];
            if ($secondsInactive >= $this->expireAfterSeconds) {
                $this->clear();
            }
        }
    }

    /**
     * Elimina la sesión
     * @return void No devuelve nada
     */
    public function clear()
    {
        session_unset();
        session_destroy();
    }

    /**
     * Inicializa la sesión
     * @return void No devuelve nada
     */
    private function initSession()
    {
        if (!isset($_SESSION['visits'])) {
            $_SESSION['visits'] = 0;
        }

        if (!isset($_SESSION['loggedIn'])) {
            $_SESSION['loggedIn'] = false;
        }

        if (!isset($_SESSION['isAdmin'])) {
            $_SESSION['isAdmin'] = false;
        }

        if (!isset($_SESSION['username'])) {
            $_SESSION['username'] = null;
        }

        if (!isset($_SESSION['lastLoginDate'])) {
            $_SESSION['lastLoginDate'] = null;
        }

        if (isset($_SESSION['visits']) && $_SESSION['loggedIn']) {
            $_SESSION['visits']++;
        }

        $this->refreshLastActivity();
    }

    /**
     * Actualiza la última actividad
     * @return void No devuelve nada
     */
    public function refreshLastActivity()
    {
        $_SESSION['last_activity'] = time();
    }


    /**
     * Comprueba si el usuario está logueado
     * @return mixed Devuelve true si el usuario está logueado o false en caso contrario
     */
    public function isLoggedIn()
    {
        return $_SESSION['loggedIn'];
    }

    /**
     * Comprueba si el usuario es administrador
     * @return mixed Devuelve true si el usuario es administrador o false en caso contrario
     */
    public function isAdmin()
    {
        return $_SESSION['isAdmin'];
    }

    /**
     * Devuelve el número de visitas
     * @return mixed Devuelve el número de visitas
     */
    public function getVisitCount()
    {
        return $_SESSION['visits'];
    }

    /**
     * Realiza el login del usuario
     * @param $username String Nombre de usuario
     * @param $isAdmin bool Indica si el usuario es administrador
     * @return void No devuelve nada
     */
    public function login($username, $isAdmin)
    {
        $_SESSION['loggedIn'] = true;
        $_SESSION['isAdmin'] = $isAdmin;
        $_SESSION['username'] = $username;
        $_SESSION['lastLoginDate'] = date('Y-m-d H:i:s');
        $this->refreshLastActivity();
    }

    /**
     * Realiza el logout del usuario
     * @return void No devuelve nada
     */
    public function logout()
    {
        $_SESSION['loggedIn'] = false;
        $_SESSION['isAdmin'] = false;
        $_SESSION['username'] = null;
        $_SESSION['visits'] = 0;
        $_SESSION['lastLoginDate'] = null;
    }

    /**
     * Devuelve el nombre de usuario
     * @return mixed Devuelve el nombre de usuario
     */
    public function getUsername()
    {
        return $_SESSION['username'];
    }

    /**
     * Devuelve la fecha del último login
     * @return mixed Devuelve la fecha del último login
     */
    public function getLastLoginDate()
    {
        return $_SESSION['lastLoginDate'];
    }

}