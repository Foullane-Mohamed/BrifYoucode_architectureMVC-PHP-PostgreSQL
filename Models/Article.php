<?php
class Article {
    private $conn;
    private $table_name = "articles";

    public $id;
    public $title;
    public $content;
    public $user_id;
    public $created_at;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create() {
        $query = "INSERT INTO " . $this->table_name . "
                 (title, content, user_id, created_at)
                 VALUES
                 (:title, :content, :user_id, :created_at)";

        $stmt = $this->conn->prepare($query);

        $this->title = htmlspecialchars(strip_tags($this->title));
        $this->content = htmlspecialchars(strip_tags($this->content));
        $this->created_at = date('Y-m-d H:i:s');

        $stmt->bindParam(":title", $this->title);
        $stmt->bindParam(":content", $this->content);
        $stmt->bindParam(":user_id", $this->user_id);
        $stmt->bindParam(":created_at", $this->created_at);

        return $stmt->execute();
    }

    public function read($id = null) {
        if($id) {
            $query = "SELECT a.id, a.title, a.content, a.created_at, u.username 
                     FROM " . $this->table_name . " a
                     LEFT JOIN users u ON a.user_id = u.id
                     WHERE a.id = ? LIMIT 1";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $id);
        } else {
            $query = "SELECT a.id, a.title, a.content, a.created_at, u.username 
                     FROM " . $this->table_name . " a
                     LEFT JOIN users u ON a.user_id = u.id
                     ORDER BY a.created_at DESC";
            $stmt = $this->conn->prepare($query);
        }

        $stmt->execute();
        return $stmt;
    }

    public function update() {
        $query = "UPDATE " . $this->table_name . "
                 SET title = :title, content = :content
                 WHERE id = :id AND user_id = :user_id";

        $stmt = $this->conn->prepare($query);

        $this->title = htmlspecialchars(strip_tags($this->title));
        $this->content = htmlspecialchars(strip_tags($this->content));

        $stmt->bindParam(":title", $this->title);
        $stmt->bindParam(":content", $this->content);
        $stmt->bindParam(":id", $this->id);
        $stmt->bindParam(":user_id", $this->user_id);

        return $stmt->execute();
    }

    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " 
                 WHERE id = :id AND user_id = :user_id";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":id", $this->id);
        $stmt->bindParam(":user_id", $this->user_id);

        return $stmt->execute();
    }
}