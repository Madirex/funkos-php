<?php

namespace models;

/**
 * Class Funko
 */
class Funko
{
    public static $IMAGE_DEFAULT = 'https://www.madirex.com/favicon.ico';
    private $id;
    private $uuid;
    private $description;
    private $image;
    private $price;
    private $stock;
    private $createdAt;
    private $updatedAt;
    private $categoryId;
    private $categoryName;
    private $isDeleted;

    /**
     * @param $id int ID del funko
     * @param $uuid String ID del funko
     * @param $description String Descripción del funko
     * @param $image String Imagen del funko
     * @param $price int Precio del funko
     * @param $stock int Stock del funko
     * @param $createdAt String Fecha de creación del funko
     * @param $updatedAt String Fecha de actualización del funko
     * @param $categoryId int ID de la categoría
     * @param $categoryName String Nombre de la categoría
     * @param $isDeleted bool Indica si el funko está eliminado
     */
    public function __construct($id = null, $uuid = null, $description = null, $image = null, $price = null, $stock = null, $createdAt = null, $updatedAt = null, $categoryId = null, $categoryName = null, $isDeleted = null)
    {
        $this->id = $id;
        $this->uuid = $uuid;
        $this->description = $description;
        $this->image = $image ?? self::$IMAGE_DEFAULT;
        $this->price = $price;
        $this->stock = $stock;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->categoryId = $categoryId;
        $this->categoryName = $categoryName;
        $this->isDeleted = $isDeleted;
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
