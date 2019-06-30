<?php

class CommentStatement implements IStatement
{
    public function getRetrieveAllStatement()
    {
        return "SELECT * FROM comments WHERE news_id = :news_id";
    }
    
    public function getInsertStatement()
    {
        return "INSERT INTO comments SET news_id = :news_id, author = :author, content = :content, created = :created";
    }   
    
    public function getUpdateStatement()
    {
        return null;
    }
    
    public function getDeleteStatement()
    {
        return null;
    }
}

?>