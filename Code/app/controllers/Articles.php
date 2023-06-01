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


    // Get the published articles and displays in a view with search bar
    public function index()
    {
        $articles = [];

        if (isset($_GET['search'])) {
            $searchTerm = trim(filter_input(INPUT_GET, 'search', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
            $articles = $this->articleModel->searchArticles($searchTerm);
            foreach ($articles as $article) {
                $tags = $this->tagModel->getTagsOfArticle($article->id);
                $article->tags = $tags;
            }
        } else {
            $articles = $this->articleModel->getPublishedArticles();
            foreach ($articles as $article) {
                $tags = $this->tagModel->getTagsOfArticle($article->id);
                $article->tags = $tags;
            }
        }

        $data = [
            'articles' => $articles
        ];

        return $this->view('articles/index', $data);
    }


    // Management for journalists and editors 
    public function manage()
    {
        if ((!userHasRole('journalist')) && (!userHasRole('editor'))) {
            redirectURL('articles/index');
            exit();
        }

        if (userHasRole('journalist')) {
            $articles = $this->articleModel->getArticlesByAuthor($_SESSION['user_id']);
        } elseif (userHasRole('editor')) {
            $editorMode = filter_input(INPUT_GET, 'editorMode', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? 'true';
            if ($editorMode == 'false') {
                $articles = $this->articleModel->getArticlesByAuthor($_SESSION['user_id']);
            } else {
                $articles = $this->articleModel->getReviewArticles();
            }
        }


        foreach ($articles as $article) {
            $tags = $this->tagModel->getTagsOfArticle($article->id);
            $article->tags = $tags;
        }

        $data = [
            'articles' => $articles,
            'editorMode' => $editorMode,
        ];

        return $this->view('articles/manage', $data);
    }


    // Create method and associated helper methods

    public function create()
    {
        if ((!userHasRole('journalist')) && (!userHasRole('editor'))) {
            redirectURL('articles/index');
            exit();
        }

        $data = $this->initArticleData();

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = $this->validateArticleData($data);
            $this->createPostRequest($data);
        } else {
            $this->view('articles/create', $data);
        }
    }

    private function createPostRequest($data)
    {
        if (empty($data['title_error']) && empty($data['body_error']) && empty($data['tags_error'])) {
            $this->articleModel->beginTransaction(); // Start transaction
            try {
                $article_id = $this->articleModel->createArticle($data);

                if (!$article_id) {
                    throw new Exception("Failed to create article");
                }

                $this->tagsController->mapTags($article_id, $data['tags']);

                $this->articleModel->commit(); // Commit transaction

                redirectURL('articles/manage');
            } catch (Exception) {
                $this->articleModel->rollBack(); // Rollback transaction in case of error
                $this->view('error');
            }
        } else {
            $this->view('articles/create', $data);
        }
    }


    // Display method and other associated helper methods

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


    // Edit method and other associated helper methods

    public function edit($id)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->editPostRequest($id);
        } else {
            $this->editGetRequest($id);
        }
    }

    private function editPostRequest($id)
    {
        $data = $this->initArticleData();
        $data = $this->validateArticleData($data);

        if (empty($data['title_error']) && empty($data['body_error']) && empty($data['tags_error'])) {
            $this->articleModel->beginTransaction(); // Start transaction

            try {
                $this->articleModel->updateArticle($data);
                $this->tagModel->deleteTagToArticle($data['id']);
                $this->tagsController->mapTags($data['id'], $data['tags']);

                $this->articleModel->commit(); // If everything is fine, commit the transaction

                redirectURL('articles/manage');
            } catch (Exception) {
                $this->articleModel->rollBack(); // If something goes wrong, rollback the transaction

                $this->view('error');
            }
        } else {
            $data['tags'] = $this->getTagsJSON($data['id']);
            $this->view('articles/edit', $data);
        }
    }

    private function editGetRequest($id)
    {
        $article = $this->articleModel->getArticleById($id);

        if (!$article || !$this->hasPermission($id)) {
            redirectURL('articles/index');
            exit();
        }

        $data = [
            'id' => $article->id,
            'title' => $article->title,
            'body' => $article->body,
            'tags' => $this->getTagsJSON($id)
        ];

        $this->view('articles/edit', $data);
    }

    private function getTagsJSON($id)
    {
        $tags = $this->tagModel->getTagsOfArticle($id);
        $tagsJSON = [];
        foreach ($tags as $tag) {
            $tagsJSON[] = ['value' => $tag->tag_name];
        }

        return json_encode($tagsJSON);
    }

    // Delete method and other associated helper methods

    public function delete($id)
    {
        // If not a POST request or user does not have the proper rights, redirect back to articles index
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !$this->hasPermission($id)) {
            redirectURL('articles/index');
            exit();
        }

        // Proceed with deletion
        if ($this->articleModel->deleteArticle($id)) {
            redirectURL('articles/manage');
        } else {
            redirectURL('pages/error');
            exit();
        }
    }

    // Helper methods used more than once

    // Initialises the article data which will store the data and errors
    private function initArticleData()
    {
        if (!empty($_POST)) {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $id = $_POST['id'] ?? '';
            $title = $_POST['title'] ?? '';
            $body = $_POST['body'] ?? '';
            $tags = $_POST['tags'] ?? '';
            $status = $_POST['status'] ?? '';
        } else {
            $id = '';
            $title = '';
            $body = '';
            $tags = '';
            $status = '';
        }

        return [
            'id' => $id,
            'title' => $title,
            'body' => $body,
            'tags' => $tags,
            'status' => $status,
            'user_id' => $_SESSION['user_id'],
            'title_error' => '',
            'body_error' => '',
            'tags_error' => '',
        ];
    }

    // Validates the article data
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

    // Check if user has rights to delete the article
    private function hasPermission($id)
    {
        $articleAuthor = $this->articleModel->getArticleAuthor($id);
        return userHasRole('editor') || $articleAuthor->id == $_SESSION['user_id'];
    }
}
