<?php

namespace App\Models;

use App\Core\Database;

class User
{
    private $db;
    private $table = "users";

    public $id;
    public $username;
    public $email;
    public $password;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function create()
    {
        $query = "INSERT INTO " . $this->table . " 
                  SET username=:username, email=:email, password=:password";
        $stmt = $this->db->prepare($query);

        $this->password = password_hash($this->password, PASSWORD_DEFAULT);

        $stmt->bindParam(":username", $this->username);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":password", $this->password);

        return $stmt->execute();
    }

    public function findByEmail($email)
    {
        $query = "SELECT * FROM " . $this->table . " WHERE email = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$email]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }
}