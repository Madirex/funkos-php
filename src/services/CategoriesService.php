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
     * @param string|null $searchTerm String Término de búsqueda
     * @return array Devuelve un array con todas las categorías
     */
    public function findAll($searchTerm = null)
    {
        $sql = "SELECT c.* FROM categories c";

        if ($searchTerm) {
            $sql .= " WHERE LOWER(c.name) LIKE LOWER(:searchTerm)";
            $sql .= " AND c.is_deleted = false";
        } else{
            $sql .= " WHERE c.is_deleted = false";
        }

        $sql .= " ORDER BY c.name ASC";

        $stmt = $this->pdo->prepare($sql);

        if ($searchTerm) {
            $searchTerm = "%$searchTerm%";
            $stmt->bindValue(':searchTerm', $searchTerm, PDO::PARAM_STR);
        }

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
        $stmt = $this->pdo->prepare("SELECT * FROM categories WHERE LOWER(name) = LOWER(:name)");
        $stmt->execute(['name' => strtolower($name)]);

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
     * Elimina una categoría por ID (borrado lógico)
     * @param $id int ID de la categoría
     * @return bool Devuelve true si se ha eliminado correctamente
     */
    public function deleteById($id)
    {
        $stmt = $this->pdo->prepare("UPDATE categories SET is_deleted = true WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    /**
     * Actualiza una categoría
     * @param $category Category Categoría a actualizar
     * @return bool Devuelve true si la categoría se ha actualizado correctamente o false si no se ha podido actualizar
     */
    public function update($category)
    {
        $nameTrimmed = trim($category->name);
        
        $stmt = $this->pdo->prepare("UPDATE categories SET name = :name, updated_at = :updated_at WHERE id = :id");

        //comprobar que no haya categoría con mismo nombre
        $categoryByName = $this->findByName($nameTrimmed);
        if ($categoryByName) {
            return false;
        }
    
        $stmt->bindValue(':name', $nameTrimmed, PDO::PARAM_STR);
        $category->updatedAt = date('Y-m-d H:i:s');
        $stmt->bindValue(':updated_at', $category->updatedAt, PDO::PARAM_STR);
        $stmt->bindValue(':id', $category->id, PDO::PARAM_STR);

        return $stmt->execute();
    }   

    /**
     * Guarda una categoría
     * @param $category Category Categoría a guardar
     * @return bool Devuelve true si la categoría se ha guardado correctamente o false si no se ha podido guardar
     */
    public function save($category)
    {
        $nameTrimmed = trim($category->name);

        $sql = "INSERT INTO categories (id, name, created_at, updated_at)
            VALUES (:id, :name, :created_at, :updated_at)";

        //comprobar que no haya categoría con mismo nombre
        $categoryByName = $this->findByName($nameTrimmed);
        if ($categoryByName) {
            return false;
        }

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id', $category->id, PDO::PARAM_STR);
        $stmt->bindValue(':name', $nameTrimmed, PDO::PARAM_STR);
        $category->createdAt = date('Y-m-d H:i:s');
        $stmt->bindValue(':created_at', $category->createdAt, PDO::PARAM_STR);
        $category->updatedAt = date('Y-m-d H:i:s');
        $stmt->bindValue(':updated_at', $category->updatedAt, PDO::PARAM_STR);

        return $stmt->execute();
    }
}