<?php

class ContactStatement implements IStatement
{
    public function getRetrieveAllStatement()
    {
        return "SELECT * FROM contact_requests ORDER BY created DESC";
    }
    
    public function getInsertStatement()
    {
        return "INSERT INTO contact_requests SET author = :author, content = :content, created = :created";
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