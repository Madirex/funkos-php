<?php

namespace services;

use models\Category;
use PDO;

require_once __DIR__ . '/../models/Category.php';

/**
 * Class CategoriesService
 */
class CategoriesService
{
    private $pdo;

    /**
     * CategoriesService constructor.
     * @param $pdo PDO Conexión a la base de datos
     */
    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Devuelve todas las categorías
     * @return array Devuelve un array con todas las categorías
     */
    public function findAll()
    {
        $stmt = $this->pdo->prepare("SELECT * FROM categories ORDER BY id ASC");
        $stmt->execute();

        $categories = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $category = new Category(
                $row['id'],
                $row['name'],
                $row['created_at'],
                $row['updated_at'],
                $row['is_deleted']
            );
            $categories[] = $category;
        }
        return $categories;
    }

    /**
     * Devuelve la categoría con el nombre indicado
     * @param $name String Nombre de la categoría
     * @return false|Category Devuelve la categoría con el nombre indicado o false si no existe
     */
    public function findByName($name)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM categories WHERE name = :name");
        $stmt->execute(['name' => $name]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
            return false;
        }
        $category = new Category(
            $row['id'],
            $row['name'],
            $row['created_at'],
            $row['updated_at'],
            $row['is_deleted']
        );
        return $category;
    }

    /**
     * Busca una categoría por ID
     * @param $id int ID de la categoría
     * @return Category|false Devuelve la categoría con el ID indicado o false si no existe
     */
    public function findById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM categories WHERE id = :id");
        $stmt->execute(['id' => $id]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
            return false;
        }
        $category = new Category(
            $row['id'],
            $row['name'],
            $row['created_at'],
            $row['updated_at'],
            $row['is_deleted']
        );
        return $category;
    }

    /**
     * Elimina una categoría por ID
     * @param $id int ID de la categoría
     * @return bool Devuelve true si se ha eliminado correctamente
     */
    public function deleteById($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM categories WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    /**
     * Actualiza una categoría
     * @param $category Category Categoría a actualizar
     * @return bool Devuelve true si la categoría se ha actualizado correctamente o false si no se ha podido actualizar
     */
    public function update($category)
    {
        $stmt = $this->pdo->prepare("UPDATE categories SET name = :name, updated_at = :updated_at WHERE id = :id");
        
        $category->setUpdatedAt(date('Y-m-d H:i:s'));

        return $stmt->execute([
            'id' => $category->getId(),
            'name' => $category->getName(),
            'updated_at' => $category->getUpdatedAt(),
        ]);
    }   

    /**
     * Guarda una categoría
     * @param $category Category Categoría a guardar
     * @return bool Devuelve true si la categoría se ha guardado correctamente o false si no se ha podido guardar
     */
    public function save($category)
    {
        $stmt = $this->pdo->prepare("INSERT INTO categories (name) VALUES (:name)");
        $category->setCreatedAt(date('Y-m-d H:i:s'));
        $category->setUpdatedAt(date('Y-m-d H:i:s'));
        $stmt->execute(['name' => $category->getName(),
        'created_at' => $category->getCreatedAt(),
        'updated_at' => $category->getUpdatedAt()]);
    }
}