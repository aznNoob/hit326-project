<?php

class Articles extends Controller
{
    private $articleModel;

    public function __construct()
    {
        $this->articleModel = $this->model('Article');
    }

    public function index()
    {
        $articles = $this->articleModel->getArticles();
        $data = [
            'articles' => $articles
        ];

        return $this->view('articles/index', $data);
    }

    public function create()
    {
        if ((!userHasRole('journalist')) && (!userHasRole('editor'))) {
            redirectURL('articles/index');
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = $this->initArticleData();
            $data = $this->validateArticleData($data);

            if (empty($data['title_error']) && empty($data['body_error'])) {
                if ($this->articleModel->createArticle($data)) {
                    redirectURL('articles/index');
                } else {
                    die('An error has occurred');
                }
            } else {
                $this->view('articles/create', $data);
            }
        } else {
            $data = $this->initArticleData();
            $this->view('articles/create', $data);
        }
    }

    public function display($id)
    { {
            $article = $this->articleModel->getArticleById($id);
            $data = [
                'articles' => $article
            ];
            $this->view('articles/show', $data);
        }
    }

    private function initArticleData()
    {
        if (!empty($_POST)) {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $title = $_POST['title'] ?? '';
            $body = $_POST['body'] ?? '';
        } else {
            $title = '';
            $body = '';
        }

        return [
            'title' => $title,
            'body' => $body,
            'user_id' => $_SESSION['user_id'],
            'title_error' => '',
            'body_error' => '',
        ];
    }

    private function validateArticleData($data)
    {
        if (empty($data['title'])) {
            $data['title_error'] = 'Please enter a title';
        }

        if (empty($data['body'])) {
            $data['body_error'] = 'Please enter a body message';
        }

        return $data;
    }
}
