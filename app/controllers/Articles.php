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
}
