<?php
namespace models;

use Ramsey\Uuid\Uuid;
class Categoria
{
    private $id;
    private $nameCategory;
    private $createdAt;
    private $updatedAt;
    private $isDeleted;

    public function __construct(
        $id = null,
        $nameCategory = null,
        $createdAt = null,
        $updatedAt = null,
        $isDeleted = null
    ){
        $this->id = $id;
        $this->nameCategory = $nameCategory;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->isDeleted = $isDeleted;
    }

    public function getId()
    {
        return $this->id;
    }

    public function __get($name)
    {
        return $this->$name;
    }

    public function __set($name, $value)
    {
        $this->$name = $value;
    }

    private function generateUUID()
    {
        return Uuid::uuid4()->toString();
    }
}