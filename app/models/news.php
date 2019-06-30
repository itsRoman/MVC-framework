<?php

class News extends Parameters
{
    
}

class NewsIterator extends LazyResultsetIterator
{
    public function next()
    {
        if($row = parent::next()) return new News($row);
        return false;
    }
}

class NewsMapper extends GenericMapper
{
    public function __construct(PDO $db)
    {
        parent::__construct($db, new NewsStatement());
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
        return new NewsIterator($stmt);
    }
    
    public function retrieveAllWithCommentsCount()
    {
        $stmt = $this->db->prepare($this->statement->getWithCommentsCountStatement());
        $stmt->execute();
        return new NewsIterator($stmt);
    }
    
    public function insert(News $news)
    {
        $stmt = $this->db->prepare($this->statement->getInsertStatement());
        return $stmt->execute(array(
            ":author_id" => 1,
            ":headline" => $news->headline,
            ":content" => $news->content,
            ":created" => $news->created,
            ":published" => isset($news->published)
        ));
    }
    
    public function update(News $news)
    {
        $stmt = $this->db->prepare($this->statement->getUpdateStatement());
        return $stmt->execute(array(
            ":headline" => $news->headline,
            ":content" => $news->content,
            ":published" => isset($news->published),
            ":id" => $news->id
        ));
    }
    
    public function deleteByID($id)
    {
        $stmt = $this->db->prepare($this->statement->getDeleteByIDStatement());
        return $stmt->execute(array(
            ":id" => $id
        ));
    }
}

?>