<?php

class Tag
{
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    public function createTag($data)
    {
        $this->db->query('INSERT INTO tags(tag) VALUES(:tag)');
        $this->db->bind(':tag', $data);
        if ($this->db->execute()) {
            return $this->db->lastInsertId();
        } else {
            return false;
        }
    }

    public function mapTagToArticle($article, $tag)
    {
        $this->db->query('INSERT INTO mapping_articles_tags(article_id, tag_id) VALUES(:article_id, :tag_id)');
        $this->db->bind(':article_id', $article);
        $this->db->bind(':tag_id', $tag);
        if ($this->db->execute()) {
            return $this->db->lastInsertId();
        } else {
            return false;
        }
    }

    public function getAllTags()
    {
        $this->db->query('SELECT * from tags;');
        $tagResults = $this->db->resultSet();
        return $tagResults;
    }

    public function findTag($data)
    {
        $this->db->query('SELECT tag FROM tags WHERE tag = :tag_name');
        $this->db->bind(':tag_name', $data);
        $this->db->resultSingle();
        if ($this->db->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function getTagsOfArticle($id)
    {
        $this->db->query('SELECT tags.tag AS tag_name 
                        FROM mapping_articles_tags 
                        JOIN tags ON mapping_articles_tags.tag_id = tags.id
                        WHERE mapping_articles_tags.article_id = :articles_id');
        $this->db->bind(':articles_id', $id);
        $tagResults = $this->db->resultSet();
        return $tagResults;
    }

    public function findTagIDByName($data)
    {
        $this->db->query('SELECT id FROM tags WHERE tag = :tag_name');
        $this->db->bind(':tag_name', $data);
        return $this->db->resultSingle();
    }
}
