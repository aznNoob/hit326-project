<?php
class Pages extends Controller
{

    private $articleModel;

    public function __construct()
    {
        $this->articleModel = $this->model('Article');
    }

    public function index()
    {
        $topArticles = $this->articleModel->getSixLatestArticles();
        $data = [
            'articles' => $topArticles
        ];
        $this->view('index', $data);
    }

    public function about()
    {
        $this->view('about');
    }
}
