<?php

namespace models;

use Ramsey\Uuid\Uuid;

/**
 * Class Category
 */
class Category
{
    private $id;
    private $name;
    private $createdAt;
    private $updatedAt;
    private $isDeleted;

    /**
     * Category constructor.
     * @param $id String ID de la categoría
     * @param $name String Nombre de la categoría
     * @param $createdAt String Fecha de creación de la categoría
     * @param $updatedAt String Fecha de actualización de la categoría
     * @param $isDeleted bool Indica si la categoría está eliminada
     */
    public function __construct($id = null, $name = null, $createdAt = null, $updatedAt = null, $isDeleted = null)
    {
        $this->id = $id;
        $this->name = $name;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->isDeleted = $isDeleted;
    }

    /**
     * @return String|null Devuelve el ID de la categoría
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Getter mágico
     * @param $name String Nombre de la categoría
     * @return mixed Devuelve el nombre de la categoría
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

    /**
     * Genera un UUID y lo devuelve como String
     * @return String Devuelve el UUID en formato String
     */
    private function generateUUID()
    {
        return Uuid::uuid4()->toString();
    }
}
