<?php

class Article
{
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    public function getArticles()
    {
        $this->db->query('SELECT articles.id, articles.title, articles.created_at, articles.body, users.name FROM articles, users WHERE articles.user_id = users.id;');
        $articleResults = $this->db->resultSet();
        return $articleResults;
    }
}
