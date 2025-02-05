<?php
namespace App\Models;

use App\Core\Database;

class Article {
    protected $connection;

    public function __construct() {
        $this->connection = Database::getInstance();
    }
    
    public function getArticles() {
        $query = "SELECT * FROM article";
        $stmt = $this->connection->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();
        $articles = [];
        
        while ($row = $result->fetch_assoc()) {
            $articles[] = $row;
        }
        
        return $articles;
    }

    public function getArticleById($id) {
        $query = "SELECT * FROM article WHERE id = ?";
        $stmt = $this->connection->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        return $result->fetch_assoc();
    }
    
    public function createArticle($data) {
        $query = "INSERT INTO article (title, content) VALUES (?, ?)";
        $stmt = $this->connection->prepare($query);
        $stmt->bind_param("ss", $data['title'], $data['content']);
        return $stmt->execute();
    }
    
    public function updateArticle($id, $data) {
        $query = "UPDATE article SET title = ?, content = ? WHERE id = ?";
        $stmt = $this->connection->prepare($query);
        $stmt->bind_param("ssi", $data['title'], $data['content'], $id);
        return $stmt->execute();
    }
    
    public function deleteArticle($id) {
        $query = "DELETE FROM article WHERE id = ?";
        $stmt = $this->connection->prepare($query);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}