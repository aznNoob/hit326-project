<?php

class Articles extends Controller
{
    private $articleModel;
    private $tagsController;
    private $tagModel;

    public function __construct()
    {
        $this->articleModel = $this->model('Article');
        $this->tagsController = $this->controller('Tags');
        $this->tagModel = $this->model('Tag');
    }

    public function index()
    {
        $articles = $this->articleModel->getArticles();
        foreach ($articles as $article) {
            $tags = $this->tagModel->getTagsOfArticle($article->id);
            $article->tags = $tags;
        }
        $data = [
            'articles' => $articles
        ];
        return $this->view('articles/index', $data);
    }

    public function create()
    {
        if ((!userHasRole('journalist')) && (!userHasRole('editor'))) {
            redirectURL('articles/index');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = $this->initArticleData();
            $data = $this->validateArticleData($data);

            if (empty($data['title_error']) && empty($data['body_error']) && empty($data['tags_error'])) {
                if ($article_id = $this->articleModel->createArticle($data)) {
                    $this->tagsController->mapTags($article_id, $data['tags']);
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
    {
        $article = $this->articleModel->getArticleById($id);
        $tags = $this->tagModel->getTagsOfArticle($article->id);
        $article->tags = $tags;
        $data = [
            'articles' => $article
        ];
        $this->view('articles/display', $data);
    }

    private function generateRelatedArticles($id)
    {
    }

    private function initArticleData()
    {
        if (!empty($_POST)) {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $title = $_POST['title'] ?? '';
            $body = $_POST['body'] ?? '';
            $tags = $_POST['tags'] ?? '';
        } else {
            $title = '';
            $body = '';
            $tags = '';
        }

        return [
            'title' => $title,
            'body' => $body,
            'tags' => $tags,
            'user_id' => $_SESSION['user_id'],
            'title_error' => '',
            'body_error' => '',
            'tags_error' => ''
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

        if (empty($data['tags'])) {
            $data['tags_error'] = 'Please add at least one tag';
        }

        return $data;
    }
}
