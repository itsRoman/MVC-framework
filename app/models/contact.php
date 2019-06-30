<?php

class Contact extends Parameters
{
    
}

class ContactIterator extends LazyResultsetIterator
{
    public function next()
    {
        if($row = parent::next()) return new Contact($row);
        return false;
    }
}

class ContactMapper extends GenericMapper
{
    public function __construct(PDO $db)
    {
        parent::__construct($db, new ContactStatement());
    }
    
    public function retrieveByID($id)
    {
        $stmt = $this->db->prepare($this->statement->getRetrieveByIDStatement());
        $stmt->execute(array(":id" =>(int)$id));
        return new News($stmt->fetch());
    }
    
    public function retrieveAll()
    {
        $stmt = $this->db->prepare($this->statement->getRetrieveAllStatement());
        $stmt->execute();
        return new ContactIterator($stmt);
    }
    
    public function insert(Contact $contact)
    {
        $stmt = $this->db->prepare($this->statement->getInsertStatement());
        return $stmt->execute(array(
            ":author" => $contact->author,
            ":content" => $contact->content,
            ":created" => $contact->created
        ));
    }
}

?>