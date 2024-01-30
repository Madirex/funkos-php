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
}