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
        $articles = $this->articleModel->getPublishedArticles();
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
            return;
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

    public function edit($id)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = $this->initArticleData();
            $data = $this->validateArticleData($data);
            if (empty($data['title_error']) && empty($data['body_error']) && empty($data['tags_error'])) {
                if ($this->articleModel->updateArticle($data)) {
                    $this->tagModel->deleteTagToArticle($data['id']);
                    $this->tagsController->mapTags($data['id'], $data['tags']);
                    redirectURL('articles/index');
                } else {
                    die('An error has occurred');
                }
            } else {
                $this->view('articles/create', $data);
            }
        } else {
            $article = $this->articleModel->getArticleById($id);
            $tags = $this->tagModel->getTagsOfArticle($id);
            $tagsJSON = [];
            foreach ($tags as $tag) {
                $tagsJSON[] = ['value' => $tag->tag_name];
            }
            $tagsJSON = json_encode($tagsJSON);
            if (!$this->hasPermission($id)) {
                redirectURL('articles/index');
            }

            $data = [
                'id' => $article->id,
                'title' => $article->title,
                'body' => $article->body,
                'tags' => $tagsJSON
            ];

            $this->view('articles/edit', $data);
        }
    }

    public function delete($id)
    {
        // If not a POST request or user does not have the proper rights, redirect back to articles index
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !$this->hasPermission($id)) {
            redirectURL('articles/index');
            return;
        }

        // Proceed with deletion
        if ($this->articleModel->deleteArticle($id)) {
            redirectURL('articles/index');
        } else {
            die('An error has occurred');
        }
    }

    // Check if user has rights to delete the article
    private function hasPermission($id)
    {
        $articleAuthor = $this->articleModel->getArticleAuthor($id);
        return userHasRole('editor') || $articleAuthor->id == $_SESSION['user_id'];
    }

    public function display($id)
    {
        $article = $this->articleModel->getArticleById($id);
        $tags = $this->tagModel->getTagsOfArticle($article->id);
        $relatedArticles = $this->generateRelatedArticles($id);
        $article->tags = $tags;
        $data = [
            'article' => $article,
            'related_articles' => $relatedArticles
        ];
        $this->view('articles/display', $data);
    }

    private function generateRelatedArticles($id, $totalLimit = 6)
    {
        $tags = $this->tagModel->getTagsOfArticle($id);
        $tagCount = count($tags);
        if ($tagCount == 0) {
            return;
        }
        $relatedArticles = [];
        $limitPerTag = (int) ceil($totalLimit / $tagCount);
        foreach ($tags as $tag) {
            $articles = $this->articleModel->getRelatedArticles($tag->id, $id, $limitPerTag);
            $relatedArticles = array_merge($relatedArticles, $articles);
        }
        $relatedArticles = array_unique($relatedArticles, SORT_REGULAR);
        return $relatedArticles;
    }

    private function initArticleData()
    {
        if (!empty($_POST)) {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $id = $_POST['id'] ?? '';
            $title = $_POST['title'] ?? '';
            $body = $_POST['body'] ?? '';
            $tags = $_POST['tags'] ?? '';
        } else {
            $id = '';
            $title = '';
            $body = '';
            $tags = '';
        }

        return [
            'id' => $id,
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
