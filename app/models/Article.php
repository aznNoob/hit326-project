<?php

class Article
{
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    public function beginTransaction()
    {
        return $this->db->beginTransaction();
    }

    public function commit()
    {
        return $this->db->commit();
    }

    public function rollback()
    {
        return $this->db->rollback();
    }

    public function getPublishedArticles($limit = null)
    {
        $sql = "SELECT articles.id, articles.title, articles.created_at, articles.body, articles.status_time, users.name
                FROM articles
                JOIN users ON articles.user_id = users.id
                WHERE articles.status = 'published'
                ORDER BY articles.status_time DESC";

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

    public function searchArticles($search)
    {
        $likeTerm = "%$search%";
        $this->db->query("SELECT articles.*, users.name FROM articles
                        JOIN users ON articles.user_id = users.id
                        WHERE articles.status = 'published'
                        AND (title LIKE :search OR body LIKE :search OR users.name LIKE :search)
                        ORDER BY articles.status_time DESC");
        $this->db->bind(':search', $likeTerm);
        $searchResults = $this->db->resultSet();
        return $searchResults;
    }

    public function getArticlesByAuthor($id)
    {
        $this->db->query("SELECT articles.*, users.name
                        FROM articles
                        JOIN users ON articles.user_id = users.id
                        WHERE articles.user_id = :user_id
                        ORDER BY articles.status_time DESC");
        $this->db->bind(':user_id', $id);
        $results = $this->db->resultSet();
        return $results;
    }

    public function getReviewArticles()
    {
        $this->db->query("SELECT articles.*, users.name
                        FROM articles
                        JOIN users ON articles.user_id = users.id
                        WHERE articles.status = 'pending_review'
                        ORDER BY articles.status ASC,
                        articles.created_at ASC");
        $results = $this->db->resultSet();
        return $results;
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
        if ($row) {
            return $row;
        } else {
            return false;
        }
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
        $this->db->query('INSERT INTO articles(user_id, title, body, status, status_time) VALUES(:user_id, :title, :body, :status, now())');
        $this->db->bind(':user_id', $data['user_id']);
        $this->db->bind(':title', $data['title']);
        $this->db->bind(':body', $data['body']);
        $this->db->bind(':status', $data['status']);
        if ($this->db->execute()) {
            return $this->db->lastInsertId();
        } else {
            throw new PDOException('Failed to create article');
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
        $this->db->query('UPDATE articles SET title = :title, body = :body, status = :status, status_time = now() WHERE id = :id');
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':title', $data['title']);
        $this->db->bind(':body', $data['body']);
        $this->db->bind(':status', $data['status']);
        if ($this->db->execute()) {
            return true;
        } else {
            throw new PDOException('Failed to update article');
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
