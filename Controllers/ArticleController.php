<?php
class ArticleController {
    private $article;

    public function __construct($db) {
        $this->article = new Article($db);
    }

    public function index() {
        $articles = $this->article->read();
        require_once('views/article/index.php');
    }

    public function create() {
        session_start();
        if(!isset($_SESSION['user_id'])) {
            header("Location: login.php");
            exit();
        }

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->article->title = $_POST['title'];
            $this->article->content = $_POST['content'];
            $this->article->user_id = $_SESSION['user_id'];

            if($this->article->create()) {
                header("Location: index.php");
                exit();
            }
        }
        require_once('views/article/create.php');
    }

    public function edit($id) {
        session_start();
        if(!isset($_SESSION['user_id'])) {
            header("Location: login.php");
            exit();
        }

        $article = $this->article->read($id)->fetch(PDO::FETCH_ASSOC);
        
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->article->id = $id;
            $this->article->title = $_POST['title'];
            $this->article->content = $_POST['content'];
            $this->article->user_id = $_SESSION['user_id'];

            if($this->article->update()) {
                header("Location: index.php");
                exit();
            }
        }
        require_once('views/article/edit.php');
    }

    public function delete($id) {
        session_start();
        if(!isset($_SESSION['user_id'])) {
            header("Location: login.php");
            exit();
        }

        $this->article->id = $id;
        $this->article->user_id = $_SESSION['user_id'];

        if($this->article->delete()) {
            header("Location: index.php");
            exit();
        }
    }
}