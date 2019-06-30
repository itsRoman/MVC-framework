<?php

class NewsStatement implements IStatement
{
    public function getRetrieveByIDStatement()
    {
        return "SELECT * FROM news WHERE id = :id";
    }
    public function getRetrieveAllStatement()
    {
        return "SELECT * FROM news ORDER BY created DESC";
    }
    
    public function getInsertStatement()
    {
        return "INSERT INTO news SET author_id = :author_id, headline = :headline, content = :content, created = :created, published = :published";
    }  
    
    public function getUpdateStatement()
    {
        return "UPDATE news SET headline = :headline, content = :content, published = :published WHERE id = :id";
    }
    
    public function getDeleteByIDStatement()
    {
        return "DELETE FROM news WHERE id = :id";
    }
    
    public function getWithCommentsCountStatement()
    {
        return "SELECT news.*, COUNT(comments.news_id) AS commentcount FROM news LEFT JOIN comments ON news.id = comments.news_id GROUP BY comments.news_id, news.id ORDER BY news.created DESC"; 
    }
}

?>