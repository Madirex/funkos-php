<?php

namespace config;


use Dotenv\Dotenv;
use PDO;


/**
 * Class Config
 */
class Config
{
    private static $instance;
    private $postgresDb;
    private $postgresUser;
    private $postgresPassword;
    private $postgresHost;
    private $postgresPort;
    private $db;
    private $rootPath = '/var/www/html/public/';
    private $uploadPath = '/var/www/html/public/uploads/';
    private $uploadUrl = 'http://localhost:8080/uploads/';

    /**
     * Config constructor
     * Carga las variables de entorno
     */
    private function __construct()
    {
        $dotenv = Dotenv::createImmutable($this->rootPath);
        $dotenv->load();
        $this->postgresDb = getenv('POSTGRES_DB') ?? 'default_db';
        $this->postgresUser = getenv('POSTGRES_USER') ?? 'default_user';
        $this->postgresPassword = getenv('POSTGRES_PASSWORD') ?? 'default_password';
        $this->postgresHost = getenv('POSTGRES_HOST') ?? 'localhost';
        $this->postgresPort = getenv('POSTGRES_PORT') ?? '5432';
        $this->db = new PDO("pgsql:host={$this->postgresHost};port={$this->postgresPort};dbname={$this->postgresDb}", $this->postgresUser, $this->postgresPassword);
    }

    /**
     * Devuelve la instancia de Config (Singleton)
     * @return Config Devuelve la instancia de Config
     */
    public static function getInstance(): Config
    {
        if (!isset(self::$instance)) {
            self::$instance = new Config();
        }
        return self::$instance;
    }


    /**
     * Devuelve la propiedad
     * @param $name string Nombre de la propiedad
     * @return mixed Devuelve el valor de la propiedad
     */
    public function __get($name)
    {
        return $this->$name;
    }

    /**
     * Establece el valor de la propiedad
     * @param $name string Nombre de la propiedad
     * @param $value mixed Valor de la propiedad
     * @return void No devuelve nada
     */
    public function __set($name, $value)
    {
        $this->$name = $value;
    }
}