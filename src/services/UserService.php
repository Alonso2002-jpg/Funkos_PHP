<?php

namespace services;

use models\User;
use Exception;
use PDO;

require_once __DIR__ . '/../models/User.php';
class UserService
{
    private $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function authenticate($username, $password): User
    {
        $user = $this->findUserByUsername($username);
        if ($user && password_verify($password, $user->password)) {
            return $user;
        }
        throw new Exception('Usuario o contraseña no válidos');
    }

    public function findUserByUsername($username)
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $userRow = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$userRow) {
            return null;
        }

        $stmtRoles = $this->db->prepare("SELECT role FROM user_roles WHERE user_id = :user_id");
        $stmtRoles->bindParam(':user_id', $userRow['id']);
        $stmtRoles->execute();
        $roles = $stmtRoles->fetchAll(PDO::FETCH_COLUMN);

        return new User(
            $userRow['id'],
            $userRow['username'],
            $userRow['password'],
            $userRow['nombre'],
            $userRow['apellidos'],
            $userRow['email'],
            $userRow['created_at'],
            $userRow['updated_at'],
            $userRow['is_deleted'],
            $roles
        );
    }

    public function save($user){
        $actPass = $user->password;
        $stmt = $this->db->prepare("INSERT INTO users (username, password, nombre, apellidos, email, created_at, updated_at) VALUES (:username, :password, :nombre, :apellidos, :email, :created_at, :updated_at)");
        $stmt->bindParam(':username', $user->username);
        $user->password = password_hash($actPass, PASSWORD_DEFAULT);
        $stmt->bindParam(':password', $user->password);
        $stmt->bindParam(':nombre', $user->name);
        $stmt->bindParam(':apellidos', $user->lastname);
        $stmt->bindParam(':email', $user->email);
        $user->createdAt = date('Y-m-d H:i:s');
        $stmt->bindValue(':created_at', $user->createdAt, PDO::PARAM_STR);
        $user->updatedAt = date('Y-m-d H:i:s');
        $stmt->bindValue(':updated_at', $user->updatedAt, PDO::PARAM_STR);
        $stmt->execute();

        $userId = $this->db->lastInsertId();
        $userRole = "USER";
        $stmtRoles = $this->db->prepare("INSERT INTO user_roles (user_id, role) VALUES (:user_id, :role)");
        $stmtRoles->bindParam(':user_id', $userId);
        $stmtRoles->bindParam(':role', $userRole);
        $stmtRoles->execute();
    }
}