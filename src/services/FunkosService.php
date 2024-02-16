<?php

namespace services;

use models\Funko;
use PDO;
use Ramsey\Uuid\Uuid;

require_once __DIR__ . '/../models/Funko.php';

/*
 * Class FunkosService
 */

class FunkosService
{
    private $pdo;

    /**
     * FunkosService constructor.
     * @param $pdo PDO Conexión a la base de datos
     */
    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Devuelve todos los Funkos con el nombre de la categoría
     * @param null $searchTerm String Término de búsqueda
     * @return array Devuelve un array con todos los funkos
     */
    public function findAllWithCategoryName($searchTerm = null)
    {
        $sql = "SELECT p.*, c.name AS category_name FROM funkos p LEFT JOIN categories c ON p.category_id = c.id";

        if ($searchTerm) {
            $sql .= " WHERE LOWER(p.description) LIKE LOWER(:searchTerm)";
        }

        $sql .= " ORDER BY p.id ASC";

        $stmt = $this->pdo->prepare($sql);

        if ($searchTerm) {
            $searchTerm = "%$searchTerm%";
            $stmt->bindValue(':searchTerm', $searchTerm, PDO::PARAM_STR);
        }

        $stmt->execute();

        $funkos = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $funko = new Funko(
                $row['id'],
                $row['uuid'],
                $row['description'],
                $row['image'],
                $row['price'],
                $row['stock'],
                $row['created_at'],
                $row['updated_at'],
                $row['category_id'],
                $row['category_name'],
                $row['is_deleted']
            );
            $funkos[] = $funko;
        }
        return $funkos;
    }

    /**
     * Busca un funko por ID
     * @param $id int ID del funko
     * @return Funko|null Devuelve el funko con el ID indicado o null si no existe
     */
    public function findById($id)
    {
        $sql = "SELECT p.*, c.name AS category_name FROM funkos p LEFT JOIN categories c ON p.category_id = c.id
            WHERE p.id = :id";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
            return null;
        }

        $funko = new Funko(
            $row['id'],
            $row['uuid'],
            $row['description'],
            $row['image'],
            $row['price'],
            $row['stock'],
            $row['created_at'],
            $row['updated_at'],
            $row['category_id'],
            $row['category_name'],
            $row['is_deleted']
        );

        return $funko;
    }

    /**
     * Busca un funko por UUID
     * @param $id int ID del funko
     * @return bool Devuelve true si el funko existe o false si no existe
     */
    public function deleteById($id)
    {
        $sql = "DELETE FROM funkos WHERE id = :id";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    /**
     * Actualiza un funko
     * @param Funko $funko Funko a actualizar
     * @return bool Devuelve true si el funko se ha actualizado correctamente o false si no se ha podido actualizar
     */
    public function update(Funko $funko)
    {
        $sql = "UPDATE funkos SET
            description = :description,
            image = :image,
            price = :price,
            stock = :stock,
            category_id = :category_id,
            updated_at = :updated_at
            WHERE id = :id";

        $stmt = $this->pdo->prepare($sql);

        $stmt->bindValue(':description', $funko->description, PDO::PARAM_STR);
        $stmt->bindValue(':image', $funko->image, PDO::PARAM_STR);
        $stmt->bindValue(':price', $funko->price, PDO::PARAM_STR);
        $stmt->bindValue(':stock', $funko->stock, PDO::PARAM_INT);
        $stmt->bindValue(':category_id', $funko->categoryId, PDO::PARAM_INT);
        $funko->updatedAt = date('Y-m-d H:i:s');
        $stmt->bindValue(':updated_at', $funko->updatedAt, PDO::PARAM_STR);
        $stmt->bindValue(':id', $funko->id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    /**
     * Guarda un funko
     * @param Funko $funko Funko a guardar
     * @return bool Devuelve true si el funko se ha guardado correctamente o false si no se ha podido guardar
     */
    public function save(Funko $funko)
    {
        $sql = "INSERT INTO funkos (uuid, description, image, price, stock, category_id, created_at, updated_at)
            VALUES (:uuid, :description, :image, :price, :stock, :category_id, :created_at, :updated_at)";

        $stmt = $this->pdo->prepare($sql);

        $funko->uuid = Uuid::uuid4()->toString();
        $stmt->bindValue(':uuid', $funko->uuid, PDO::PARAM_STR);
        $stmt->bindValue(':description', $funko->description, PDO::PARAM_STR);
        $funko->image = Funko::$IMAGE_DEFAULT;
        $stmt->bindValue(':image', $funko->image, PDO::PARAM_STR);
        $stmt->bindValue(':price', $funko->price, PDO::PARAM_STR);
        $stmt->bindValue(':stock', $funko->stock, PDO::PARAM_INT);
        $stmt->bindValue(':category_id', $funko->categoryId, PDO::PARAM_INT);
        $funko->createdAt = date('Y-m-d H:i:s');
        $stmt->bindValue(':created_at', $funko->createdAt, PDO::PARAM_STR);
        $funko->updatedAt = date('Y-m-d H:i:s');
        $stmt->bindValue(':updated_at', $funko->updatedAt, PDO::PARAM_STR);

        return $stmt->execute();
    }
}
