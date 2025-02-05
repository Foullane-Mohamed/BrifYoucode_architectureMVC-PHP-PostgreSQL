<?php
namespace App\Models;

use App\Core\Database;
use App\Core\Security;

class User {
    protected $connection;

    public function __construct() {
        $this->connection = Database::getInstance();
    }
    
    public function register($username, $email, $password) {
        // Check if user already exists
        $checkQuery = "SELECT * FROM users WHERE email = ?";
        $checkStmt = $this->connection->prepare($checkQuery);
        $checkStmt->bind_param("s", $email);
        $checkStmt->execute();
        $result = $checkStmt->get_result();
        
        if ($result->num_rows > 0) {
            return false; // User already exists
        }

        // Hash password
        $hashedPassword = Security::hashPassword($password);
        
        // Insert new user
        $query = "INSERT INTO users (username, email, password, salt) VALUES (?, ?, ?, ?)";
        $stmt = $this->connection->prepare($query);
        $stmt->bind_param("ssss", $username, $email, $hashedPassword['password'], $hashedPassword['salt']);
        
        return $stmt->execute();
    }

    public function login($email, $password) {
        $query = "SELECT * FROM users WHERE email = ?";
        $stmt = $this->connection->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 0) {
            return false; // User not found
        }

        $user = $result->fetch_assoc();
        
        // Verify password
        if (Security::verifyPassword($password, $user['password'], $user['salt'])) {
            return $user;
        }

        return false;
    }

    public function getUserById($id) {
        $query = "SELECT * FROM users WHERE id = ?";
        $stmt = $this->connection->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        return $result->fetch_assoc();
    }
}