<?php
class User {
    private $conn;
    private $table_name = "users";

    public $id;
    public $username;
    public $email;
    public $password;
    public $created_at;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create() {
        $query = "INSERT INTO " . $this->table_name . "
                 (username, email, password, created_at)
                 VALUES
                 (:username, :email, :password, :created_at)";

        $stmt = $this->conn->prepare($query);

        $this->username = htmlspecialchars(strip_tags($this->username));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->password = htmlspecialchars(strip_tags($this->password));
        $this->created_at = date('Y-m-d H:i:s');

        $stmt->bindParam(":username", $this->username);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":password", password_hash($this->password, PASSWORD_DEFAULT));
        $stmt->bindParam(":created_at", $this->created_at);

        return $stmt->execute();
    }

    public function login($email, $password) {
        $query = "SELECT id, username, password FROM " . $this->table_name . " 
                 WHERE email = ? LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $email);
        $stmt->execute();

        if($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            if(password_verify($password, $row['password'])) {
                return $row;
            }
        }
        return false;
    }

    public function read($id = null) {
        if($id) {
            $query = "SELECT id, username, email, created_at FROM " . $this->table_name . " 
                     WHERE id = ? LIMIT 1";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $id);
        } else {
            $query = "SELECT id, username, email, created_at FROM " . $this->table_name;
            $stmt = $this->conn->prepare($query);
        }

        $stmt->execute();
        return $stmt;
    }
}