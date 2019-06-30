<?php

class UserStatement implements IStatement
{
    public function getSelectStatement()
    {
        return "SELECT * FROM users";
    }
    
    public function getByNameStatement()
    {
        return "SELECT * FROM users WHERE name = :name";
    }
    
    public function getInsertStatement()
    {
        return "INSERT INTO users SET name = :name, email = :email, pw = MD5(:pw), registered = :registered";

    }
    
    public function getLoginStatement()
    {
        return "SELECT * FROM users WHERE name = :name AND pw = MD5(:pw)";
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