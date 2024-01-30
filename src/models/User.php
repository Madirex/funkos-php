<?php

namespace models;

/**
 * Class User
 */
class User
{
    public $id;
    public $username;
    public $password;

    public $name;
    public $surnames;
    public $email;
    public $createdAt;
    public $updatedAt;
    public $isDeleted;
    public $roles = [];

    /**
     * @param $id String ID del usuario
     * @param $username String Nombre de usuario
     * @param $password String Contraseña del usuario
     * @param $name String Nombre del usuario
     * @param $surnames String Apellidos del usuario
     * @param $email String Email del usuario
     * @param $createdAt String Fecha de creación del usuario
     * @param $updatedAt String Fecha de actualización del usuario
     * @param $isDeleted bool Indica si el usuario está eliminado
     * @param $roles array Roles del usuario
     */
    public function __construct($id, $username, $password, $name, $surnames, $email, $createdAt, $updatedAt, $isDeleted, $roles = [])
    {
        $this->id = $id;
        $this->username = $username;
        $this->password = $password;
        $this->name = $name;
        $this->surnames = $surnames;
        $this->email = $email;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->isDeleted = $isDeleted;
        $this->roles = $roles;
    }

    /**
     * Getter mágico
     * @param $name String Nombre de la propiedad
     * @return mixed Devuelve el valor de la propiedad
     */
    public function __get($name)
    {
        return $this->$name;
    }

    /**
     * Setter mágico
     * @param $name String Nombre de la propiedad
     * @param $value String Valor de la propiedad
     * @return void No devuelve nada
     */
    public function __set($name, $value)
    {
        $this->$name = $value;
    }
}
