<?php
namespace models;
class Funko
{
    public static $IMAGEN_DEFAULT = 'https://via.placeholder.com/150';
    private $id;
    private $name;
    private $price;
    private $quantity;
    private $img;
    private $createdAt;
    private $updatedAt;
    private $categoryId;
    private $categoryName;
    private $isDeleted;

    public function __construct(
        $id = null,
        $name = null,
        $price = null,
        $quantity = null,
        $img = null,
        $createdAt = null,
        $updatedAt = null,
        $categoryId = null,
        $categoryName = null,
        $isDeleted = null
    ){
        $this->id = $id;
        $this->name = $name;
        $this->price = $price;
        $this->quantity = $quantity;
        $this->img = isset($img) ? $img : self::$IMAGEN_DEFAULT;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->categoryId = $categoryId;
        $this->categoryName = $categoryName;
        $this->isDeleted = $isDeleted;
    }

    public function __get($name){
        return $this->$name;
    }

    public function __set($name, $value){
        $this->$name = $value;
    }
}