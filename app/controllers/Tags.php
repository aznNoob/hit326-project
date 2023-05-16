<?php

class Tags extends Controller
{
    private $tagModel;

    public function __construct()
    {
        $this->tagModel = $this->model('Tag');
    }

    public function create($data)
    {
        $tags = $this->formatTag($data);
        foreach ($tags as $tag) {
            if (!$this->tagModel->findTag($tag)) {
                $this->tagModel->createTag($tag);
            }
        }
        return $tags;
    }

    public function mapTags($article, $tags)
    {
        $tagsArray = $this->create($tags);
        foreach ($tagsArray as $tag) {
            $tag = $this->tagModel->findTagIDByName($tag);
            $this->tagModel->mapTagToArticle($article, $tag->id);
        }
        return true;
    }

    /*
    ** Helper Methods
    */

    public function formatTag($data)
    {
        $tagArray = explode(",", $data);
        $tagArray = array_map(function ($tag) {
            return ucfirst(trim($tag));
        }, $tagArray);
        return $tagArray;
    }
}
