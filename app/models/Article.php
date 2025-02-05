<?php

namespace App\Models;

use App\Core\Database;

class Article
{
    private $db;
    private $table = "articles";

    public $id;
    public $title;
    public $content;
    public $user_id;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getAll()
    {
        $query = "SELECT a.*, u.username FROM " . $this->table . " a 
                  LEFT JOIN users u ON a.user_id = u.id 
                  ORDER BY a.created_at DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getById($id)
    {
        $query = "SELECT a.*, u.username FROM " . $this->table . " a 
                  LEFT JOIN users u ON a.user_id = u.id 
                  WHERE a.id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function create()
    {
        $query = "INSERT INTO " . $this->table . " 
                  SET title=:title, content=:content, user_id=:user_id";
        $stmt = $this->db->prepare($query);

        $stmt->bindParam(":title", $this->title);
        $stmt->bindParam(":content", $this->content);
        $stmt->bindParam(":user_id", $this->user_id);

        return $stmt->execute();
    }

    public function update()
    {
        $query = "UPDATE " . $this->table . " 
                  SET title=:title, content=:content 
                  WHERE id=:id AND user_id=:user_id";
        $stmt = $this->db->prepare($query);

        $stmt->bindParam(":title", $this->title);
        $stmt->bindParam(":content", $this->content);
        $stmt->bindParam(":id", $this->id);
        $stmt->bindParam(":user_id", $this->user_id);

        return $stmt->execute();
    }

    public function delete($id, $user_id)
    {
        $query = "DELETE FROM " . $this->table . " WHERE id=:id AND user_id=:user_id";
        $stmt = $this->db->prepare($query);
        
        $stmt->bindParam(":id", $id);
        $stmt->bindParam(":user_id", $user_id);

        return $stmt->execute();
    }
}