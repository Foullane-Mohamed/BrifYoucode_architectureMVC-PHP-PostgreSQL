<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Article;

class ArticleController extends Controller
{
    private $articleModel;

    public function __construct() 
    {
        parent::__construct(); // Important: appel du constructeur parent pour initialiser Twig
        $this->articleModel = new Article();
    }

    // Affiche la liste des articles
    public function index()
    {
        $articles = $this->articleModel->getArticles();
        $this->render('article/index', ['articles' => $articles]);
    }

    // Affiche un article spécifique
    public function show($id)
    {
        $article = $this->articleModel->getArticleById($id);
        if (!$article) {
            // Redirection vers la page 404 si l'article n'existe pas
            $this->render('404');
            return;
        }
        $this->render('article/show', ['article' => $article]);
    }

    // Affiche le formulaire de création
    public function create()
    {
        $this->render('article/create');
    }

    // Traite la soumission du formulaire de création
    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Récupération et validation des données
            $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING);
            $content = filter_input(INPUT_POST, 'content', FILTER_SANITIZE_STRING);

            $errors = [];
            if (empty($title)) {
                $errors['title'] = 'Le titre est requis';
            }
            if (empty($content)) {
                $errors['content'] = 'Le contenu est requis';
            }

            if (empty($errors)) {
                // Création de l'article
                $created = $this->articleModel->createArticle([
                    'title' => $title,
                    'content' => $content
                ]);

                if ($created) {
                    header('Location: /article');
                    exit;
                } else {
                    $errors['general'] = 'Une erreur est survenue lors de la création de l\'article';
                }
            }

            // En cas d'erreur, réafficher le formulaire avec les erreurs
            $this->render('article/create', [
                'errors' => $errors,
                'old' => ['title' => $title, 'content' => $content]
            ]);
            return;
        }

        // Si ce n'est pas une requête POST, rediriger vers le formulaire
        header('Location: /article/create');
        exit;
    }

    // Affiche le formulaire de modification
    public function edit($id)
    {
        $article = $this->articleModel->getArticleById($id);
        if (!$article) {
            $this->render('404');
            return;
        }
        $this->render('article/edit', ['article' => $article]);
    }

    // Traite la soumission du formulaire de modification
    public function update($id)
    {
        $article = $this->articleModel->getArticleById($id);
        if (!$article) {
            $this->render('404');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING);
            $content = filter_input(INPUT_POST, 'content', FILTER_SANITIZE_STRING);

            $errors = [];
            if (empty($title)) {
                $errors['title'] = 'Le titre est requis';
            }
            if (empty($content)) {
                $errors['content'] = 'Le contenu est requis';
            }

            if (empty($errors)) {
                $updated = $this->articleModel->updateArticle($id, [
                    'title' => $title,
                    'content' => $content
                ]);

                if ($updated) {
                    header('Location: /article');
                    exit;
                } else {
                    $errors['general'] = 'Une erreur est survenue lors de la modification de l\'article';
                }
            }

            $this->render('article/edit', [
                'article' => $article,
                'errors' => $errors
            ]);
            return;
        }

        header('Location: /article/edit/' . $id);
        exit;
    }

    // Supprime un article
    public function delete($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $article = $this->articleModel->getArticleById($id);
            if (!$article) {
                $this->render('404');
                return;
            }

            $deleted = $this->articleModel->deleteArticle($id);
            if ($deleted) {
                header('Location: /article');
                exit;
            }
        }

        // Si ce n'est pas une requête POST ou si la suppression a échoué
        header('Location: /article');
        exit;
    }
}