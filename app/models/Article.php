<?php

class Article
{
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    public function getPublishedArticles($limit = null)
    {
        $sql = "SELECT articles.id, articles.title, articles.created_at, articles.body, users.name
                FROM articles
                JOIN users ON articles.user_id = users.id
                WHERE articles.status = 'published'
                ORDER BY articles.created_at DESC";

        if ($limit !== null) {
            $sql .= ' LIMIT :limit';
        }

        $this->db->query($sql);

        if ($limit !== null) {
            $this->db->bind(':limit', $limit);
        }

        $articleResults = $this->db->resultSet();
        return $articleResults;
    }

    // Retired method, now replaced by the getArticles method with limit parameter
    // public function getSixLatestArticles()
    // { {
    //         $this->db->query('SELECT articles.id, articles.title, articles.created_at, users.name AS user_name 
    //                         FROM articles
    //                         JOIN users ON articles.user_id = users.id 
    //                         ORDER BY articles.created_at DESC 
    //                         LIMIT 6;');
    //         $articleResults = $this->db->resultSet();
    //         return $articleResults;
    //     }
    // }

    public function getArticleById($data)
    {
        $this->db->query('SELECT articles.*, users.name AS author_name, users.email AS author_email
                        FROM articles
                        JOIN users ON articles.user_id = users.id
                        WHERE articles.id = :article_id;');
        $this->db->bind(':article_id', $data);
        $row = $this->db->resultSingle();
        return $row;
    }

    public function getArticleAuthor($data)
    {
        $this->db->query('SELECT users.id, users.name, users.email, users.role
                        FROM users
                        JOIN articles ON users.id = articles.user_id
                        WHERE articles.id = :article_id;');
        $this->db->bind(':article_id', $data);
        $row = $this->db->resultSingle();
        return $row;
    }

    public function getRelatedArticles($tag, $article, $limit)
    {
        $this->db->query("SELECT articles.id, articles.title
                        FROM articles
                        JOIN mapping_articles_tags ON articles.id = mapping_articles_tags.article_id
                        JOIN tags ON tags.id = mapping_articles_tags.tag_id
                        WHERE mapping_articles_tags.tag_id = :tag_id
                        AND articles.id != :current_article_id
                        AND articles.status = 'published'
                        ORDER BY articles.created_at DESC
                        LIMIT :limit");
        $this->db->bind(':tag_id', $tag);
        $this->db->bind(':current_article_id', $article);
        $this->db->bind(':limit', $limit);
        $rows = $this->db->resultSet();
        return $rows;
    }

    public function createArticle($data)
    {
        $this->db->query('INSERT INTO articles(user_id, title, body, status) VALUES(:user_id, :title, :body, :status)');
        $this->db->bind(':user_id', $data['user_id']);
        $this->db->bind(':title', $data['title']);
        $this->db->bind(':body', $data['body']);
        $this->db->bind(':status', $data['status']);
        if ($this->db->execute()) {
            return $this->db->lastInsertId();
        } else {
            return false;
        }
    }

    public function deleteArticle($id)
    {
        $this->db->query('DELETE FROM articles WHERE articles.id = :article_id');
        $this->db->bind('article_id', $id);
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function updateArticle($data)
    {
        $this->db->query('UPDATE articles SET title = :title, body = :body, status = :status WHERE id = :id');
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':title', $data['title']);
        $this->db->bind(':body', $data['body']);
        $this->db->bind(':status', $data['status']);
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function setArticleStatus($data)
    {
        $this->db->query('UPDATE articles SET articles.status = :status WHERE articles.id = :id;');
        $this->db->bind(':status', $data['status']);
        $this->db->bind(':id', $data['id']);
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }
}
