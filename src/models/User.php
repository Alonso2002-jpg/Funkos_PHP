<?php

namespace models;

class User
{
    public $id;
    public $username;
    public $password;
    public $name;
    public $lastname;
    public $email;
    public $createdAt;
    public $updatedAt;
    public $isDeleted;
    public $roles = [];

    public function __construct($id, $username, $password, $name, $lastname, $email, $createdAt, $updatedAt, $isDeleted, $roles = [])
    {
        $this->id = $id;
        $this->username = $username;
        $this->password = $password;
        $this->name = $name;
        $this->lastname = $lastname;
        $this->email = $email;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->isDeleted = $isDeleted;
        $this->roles = $roles;
    }

    public function __get($name)
    {
        return $this->$name;
    }

    public function __set($name, $value)
    {
        $this->$name = $value;
    }
}