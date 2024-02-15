<?php

namespace services;

use models\Categoria;
use PDO;

require_once __DIR__ . '/../models/Categoria.php';
class CategoriaService
{

    private $pdo;

    public function __contruct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function findAll()
    {
        $stmt = $this->pdo->prepare("SELECT * FROM categorias ORDER BY id ASC");
        $stmt->execute();

        $categorias = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $categoria = new Categoria(
                $row['id'],
                $row['$name_category'],
                $row['created_at'],
                $row['updated_at'],
                $row['is_deleted']
            );
            $categorias[] = $categoria;
        }
        return $categorias;
    }

    public function findByName($name)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM categorias WHERE name_category = :nomCate");
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
}