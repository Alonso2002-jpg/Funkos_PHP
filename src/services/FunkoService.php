<?php

namespace services;

use models\Funko;
use PDO;
use Ramsey\Uuid\Uuid;

require_once __DIR__ . '/../models/Funko.php';
class FunkoService
{
    private $pdo;

    public function __contruct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function findAllByCategory($searchItem = null)
    {
        $statement = "SELECT f.*, c.name_category
                    FROM funkos f 
                    LEFT JOIN categorias c ON f.category_id = c.id";

        $statement .= " ORDER BY f.id ASC";

        $stmt = $this->pdo->prepare($statement);

        if ($searchItem){
            $stmt->bindValue(':searchItem', $searchItem, PDO::PARAM_STR);
        }

        $stmt->execute();

        $funkos = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            $funko = new Funko(
                $row['id'],
                $row['name'],
                $row['price'],
                $row['quantity'],
                $row['img'],
                $row['created_at'],
                $row['updated_at'],
                $row['category_id'],
                $row['name_category'],
                $row['is_deleted']
            );
            $funkos[] = $funko;
        }
        return $funkos;
    }

    public function findById($id)
    {
        $sql = "SELECT f.*, c.name_category 
            FROM funkos f
            LEFT JOIN categorias c ON f.categoria_id = c.id
            WHERE f.id = :id";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
            return null;
        }

        $funko = new Funko(
            $row['id'],
            $row['name'],
            $row['price'],
            $row['quantity'],
            $row['img'],
            $row['created_at'],
            $row['updated_at'],
            $row['category_id'],
            $row['name_category'],
            $row['is_deleted']
        );
        return  $funko;
    }

    public function deleteById($id)
    {
        $sql = "DELETE FROM funkos WHERE id = :id";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function update(Funko $funko)
    {
        $sql = "UPDATE funkos SET
            name = :name,
            price = :price,
            quantity = :quantity,
            img = :img,
            category_id = :category_id,
            updated_at = :updated_at
            WHERE id = :id";

        $stmt = $this->pdo->prepare($sql);

        $stmt->bindValue(':name', $funko->marca, PDO::PARAM_STR);
        $stmt->bindValue(':price', $funko->precio, PDO::PARAM_STR);
        $stmt->bindValue(':quantity', $funko->stock, PDO::PARAM_INT);
        $stmt->bindValue(':img', $funko->imagen, PDO::PARAM_STR);
        $stmt->bindValue(':category_id', $funko->categoryId, PDO::PARAM_INT);
        $funko->updatedAt = date('Y-m-d H:i:s');
        $stmt->bindValue(':updated_at', $funko->updatedAt, PDO::PARAM_STR);
        $stmt->bindValue(':id', $funko->id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function save(Funko $funko)
    {
        $sql = "INSERT INTO funkos (name, price, quantity, img,  category_id, created_at, updated_at)
            VALUES (:name, :price, :quantity, :img, :category_id, :created_at, :updated_at)";

        $stmt = $this->pdo->prepare($sql);

        $stmt->bindValue(':name', $funko->nombre, PDO::PARAM_STR);
        $stmt->bindValue(':price', $funko->precio, PDO::PARAM_STR);
        $stmt->bindValue(':quantity', $funko->stock, PDO::PARAM_INT);
        $funko->img = Funko::$IMAGEN_DEFAULT;
        $stmt->bindValue(':img', $funko->imagen, PDO::PARAM_STR);
        $stmt->bindValue(':category_id', $funko->categoryId, PDO::PARAM_INT);
        $funko->createdAt = date('Y-m-d H:i:s');
        $stmt->bindValue(':created_at', $funko->createdAt, PDO::PARAM_STR);
        $funko->updatedAt = date('Y-m-d H:i:s');
        $stmt->bindValue(':updated_at', $funko->updatedAt, PDO::PARAM_STR);

        return $stmt->execute();
    }
}