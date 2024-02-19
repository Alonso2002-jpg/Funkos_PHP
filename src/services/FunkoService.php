<?php

namespace services;

use models\Funko;
use PDO;

require_once __DIR__ . '/../models/Funko.php';
class FunkoService
{
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function findAllByName($searchItem = null)
    {
        $statement = "SELECT f.*, c.name_category
                FROM funko f 
                LEFT JOIN category c ON f.category_id = c.id";

        if ($searchItem) {
            $searchItem = '%' . strtolower($searchItem) . '%';
            $statement .= " WHERE LOWER(f.name) LIKE :searchItem";
        }

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

    public function findAllByCategory($searchItem = null)
    {
        $statement = "SELECT f.*, c.name_category
                FROM funko f 
                LEFT JOIN category c ON f.category_id = c.id";

        if ($searchItem) {
            $searchItem = '%' . strtolower($searchItem) . '%';
            $statement .= " WHERE LOWER(f.category_name) LIKE :searchItem";
        }

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
            FROM funko f
            LEFT JOIN category c ON f.category_id = c.id
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
        $sql = "DELETE FROM funko WHERE id = :id";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function update(Funko $funko)
    {
        $sql = "UPDATE funko SET
            name = :name,
            price = :price,
            quantity = :quantity,
            img = :img,
            category_id = :category_id,
            updated_at = :updated_at,
            category_name = :category_name
            WHERE id = :id";

        $stmt = $this->pdo->prepare($sql);

        $stmt->bindValue(':name', $funko->name, PDO::PARAM_STR);
        $stmt->bindValue(':price', $funko->price, PDO::PARAM_STR);
        $stmt->bindValue(':quantity', $funko->quantity, PDO::PARAM_INT);
        $stmt->bindValue(':img', $funko->img, PDO::PARAM_STR);
        $stmt->bindValue(':category_id', $funko->categoryId, PDO::PARAM_INT);
        $stmt->bindValue(':category_name', $funko->categoryName, PDO::PARAM_STR);
        $funko->updatedAt = date('Y-m-d H:i:s');
        $stmt->bindValue(':updated_at', $funko->updatedAt, PDO::PARAM_STR);
        $stmt->bindValue(':id', $funko->id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function save(Funko $funko)
    {
        $sql = "INSERT INTO funko (name, price, quantity, img,  category_id, created_at, updated_at, category_name)
            VALUES (:name, :price, :quantity, :img, :category_id, :created_at, :updated_at, :category_name)";

        $stmt = $this->pdo->prepare($sql);

        $stmt->bindValue(':name', $funko->name, PDO::PARAM_STR);
        $stmt->bindValue(':price', $funko->price, PDO::PARAM_STR);
        $stmt->bindValue(':quantity', $funko->quantity, PDO::PARAM_INT);
        $funko->img = Funko::$IMAGEN_DEFAULT;
        $stmt->bindValue(':img', $funko->img, PDO::PARAM_STR);
        $stmt->bindValue(':category_id', $funko->categoryId, PDO::PARAM_INT);
        $stmt->bindValue(':category_name', $funko->categoryName, PDO::PARAM_STR);
        $funko->createdAt = date('Y-m-d H:i:s');
        $stmt->bindValue(':created_at', $funko->createdAt, PDO::PARAM_STR);
        $funko->updatedAt = date('Y-m-d H:i:s');
        $stmt->bindValue(':updated_at', $funko->updatedAt, PDO::PARAM_STR);

        return $stmt->execute();
    }
}