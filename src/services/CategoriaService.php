<?php

namespace services;

use models\Categoria;
use PDO;
use Ramsey\Uuid\Uuid;

require_once __DIR__ . '/../models/Categoria.php';
class CategoriaService
{

    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function findAll()
    {
        $stmt = $this->pdo->prepare("SELECT * FROM category ORDER BY id ASC");
        $stmt->execute();

        $categorias = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $categoria = new Categoria(
                $row['id'],
                $row['name_category'],
                $row['created_at'],
                $row['updated_at'],
                $row['is_deleted']
            );
            $categorias[] = $categoria;
        }
        return $categorias;
    }

    public function save($category)
    {
        $stmt = $this->pdo->prepare("INSERT INTO category (id,name_category, is_deleted, created_at, updated_at) VALUES (:id,:nomCate, :isDeleted, :created_at, :updated_at)");
        $stmt->bindValue(':id', Uuid::uuid4()->toString(), PDO::PARAM_STR);
        $stmt->bindValue(':nomCate', $category->nameCategory, PDO::PARAM_STR);
        $stmt->bindValue(':isDeleted', $category->isDeleted, PDO::PARAM_BOOL);
        $category->createdAt = date('Y-m-d H:i:s');
        $stmt->bindValue(':created_at', $category->createdAt, PDO::PARAM_STR);
        $category->updatedAt = date('Y-m-d H:i:s');
        $stmt->bindValue(':updated_at', $category->updatedAt, PDO::PARAM_STR);

        $stmt->execute();
    }

    public function update($category)
    {
        try {
            $stmt = $this->pdo->prepare("UPDATE category SET name_category = :nomCate, is_deleted = :isDeleted, updated_at = :updated_at WHERE id = :id");
            $stmt->bindValue(':nomCate', $category->nameCategory, PDO::PARAM_STR);
            $stmt->bindValue(':isDeleted', $category->isDeleted, PDO::PARAM_BOOL);
            $category->updatedAt = date('Y-m-d H:i:s');
            $stmt->bindValue(':updated_at', $category->updatedAt, PDO::PARAM_STR);
            $stmt->bindValue(':id', $category->id, PDO::PARAM_STR);
            $stmt->execute();
        }catch (Exception $e){
            echo "<script type='text/javascript'>
            alert($e)
            window.location.href = 'updateCategoria.php?update=false';
            </script>";
        }

    }
    public function findByName($name)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM category WHERE name_category = :nomCate");
        $stmt->execute(['nomCate' => $name]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
            return false;
        }
        $categoria = new Categoria(
            $row['id'],
            $row['name_category'],
            $row['created_at'],
            $row['updated_at'],
            $row['is_deleted']
        );
        return $categoria;
    }

    public function findById($id){
        $stmt = $this->pdo->prepare("SELECT * FROM category WHERE id = :id");
        $stmt->execute(['id' => $id]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
            return null;
        }

        return new Categoria($row['id'], $row['name_category'], $row['created_at'], $row['updated_at'], $row['is_deleted']);
    }

    public function deleteById($id)
    {
        $sql = "DELETE FROM category WHERE id = :id";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        return $stmt->execute();
    }
}