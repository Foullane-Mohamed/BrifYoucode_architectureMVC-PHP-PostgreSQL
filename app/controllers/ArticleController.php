<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Article;

class ArticleController extends Controller {
    public function index() {
        $articleModel = new Article();
        $articles = $articleModel->getAll();
        $this->view('articles/index', ['articles' => $articles]);
    }

    public function create() {
        session_start();
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $title = $_POST['title'];
            $content = $_POST['content'];

            $articleModel = new Article();
            if ($articleModel->create($_SESSION['user_id'], $title, $content)) {
                header('Location: /articles');
            } else {
                echo "Article creation failed";
            }
        } else {
            $this->view('articles/create');
        }
    }
}