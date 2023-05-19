<?php
class Pages extends Controller
{

    private $articleModel;
    private $tagModel;

    public function __construct()
    {
        $this->articleModel = $this->model('Article');
        $this->tagModel = $this->model('Tag');
    }

    public function index()
    {
        $topArticles = $this->articleModel->getPublishedArticles(limit: 6);
        foreach ($topArticles as $article) {
            $tags = $this->tagModel->getTagsOfArticle($article->id);
            $article->tags = $tags;
        }

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
