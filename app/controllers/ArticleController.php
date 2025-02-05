<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Article;

class ArticleController extends Controller
{
    private $article;

    public function __construct() {
        parent::__construct();
        $this->article = new Article();
    }

    public function index()
    {
        $articles = $this->article->getAll();
        $this->render('articles/index', ['articles' => $articles]);
    }

    public function show($id)
    {
        $article = $this->article->getById($id);
        if (!$article) {
            $this->redirect('/articles');
        }
        $this->render('articles/show', ['article' => $article]);
    }

    public function create()
    {
        if (!$this->isAuthenticated()) {
            $this->redirect('/login');
        }
        $this->render('articles/create');
    }

    public function store()
    {
        if (!$this->isAuthenticated()) {
            $this->redirect('/login');
        }

        $this->article->title = $_POST['title'];
        $this->article->content = $_POST['content'];
        $this->article->user_id = $_SESSION['user_id'];

        if ($this->article->create()) {
            $this->redirect('/articles');
        }
        $this->redirect('/articles/create');
    }

    public function edit($id)
    {
        if (!$this->isAuthenticated()) {
            $this->redirect('/login');
        }

        $article = $this->article->getById($id);
        if (!$article || $article['user_id'] != $_SESSION['user_id']) {
            $this->redirect('/articles');
        }

        $this->render('articles/edit', ['article' => $article]);
    }

    public function update($id)
    {
        if (!$this->isAuthenticated()) {
            $this->redirect('/login');
        }

        $this->article->id = $id;
        $this->article->title = $_POST['title'];
        $this->article->content = $_POST['content'];
        $this->article->user_id = $_SESSION['user_id'];

        if ($this->article->update()) {
            $this->redirect('/articles');
        }
        $this->redirect('/articles/edit/' . $id);
    }

    public function delete($id)
    {
        if (!$this->isAuthenticated()) {
            $this->redirect('/login');
        }

        if ($this->article->delete($id, $_SESSION['user_id'])) {
            $this->redirect('/articles');
        }
        $this->redirect('/articles');
    }
}