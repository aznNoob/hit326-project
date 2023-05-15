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
        $this->db->query('SELECT articles.id, articles.title, articles.created_at, articles.body, users.name FROM articles, users WHERE articles.user_id = users.id ORDER BY articles.created_at DESC;');
        $articleResults = $this->db->resultSet();
        return $articleResults;
    }

    public function getSixLatestArticles()
    { {
            $this->db->query('SELECT articles.id, articles.title, articles.created_at, users.name AS user_name FROM articles, users WHERE articles.user_id = users.id ORDER BY articles.created_at DESC LIMIT 6;');
            $articleResults = $this->db->resultSet();
            return $articleResults;
        }
    }

    public function getArticleById($id)
    {
        $this->db->query('SELECT *, users.name AS user_name FROM articles, users WHERE articles.id = :id AND articles.user_id = users.id');
        $this->db->bind(':id', $id);
        $row = $this->db->resultSingle();
        return $row;
    }

    public function createArticle($data)
    {
        $this->db->query('INSERT INTO articles(user_id, title, body) VALUES(:user_id, :title, :body)');
        $this->db->bind(':user_id', $data['user_id']);
        $this->db->bind(':title', $data['title']);
        $this->db->bind(':body', $data['body']);
        if ($this->db->execute()) {
            return $this->db->lastInsertId();
        } else {
            return false;
        }
    }
}
