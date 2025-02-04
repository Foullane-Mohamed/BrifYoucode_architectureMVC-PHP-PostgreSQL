<?php

namespace App\Models;

use App\Core\Database;

class Article {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function create($userId, $title, $content) {
        $stmt = $this->db->prepare("INSERT INTO articles (user_id, title, content) VALUES (?, ?, ?)");
        return $stmt->execute([$userId, $title, $content]);
    }

    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM articles");
        return $stmt->fetchAll();
    }
}