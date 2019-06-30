<?php

class Comment extends Parameters
{
    
}

class CommentsIterator extends LazyResultsetIterator
{
    public function next()
    {
        if($row = parent::next()) return new Comment($row);
        return false;
    }
}

class CommentMapper extends GenericMapper
{
    public function __construct(PDO $db)
    {
        parent::__construct($db, new CommentStatement());
    }
    
    public function retrieveByID($id)
    {
        $stmt = $this->db->prepare($this->statement->getRetrieveByIDStatement());
        $stmt->execute(array(":id" =>(int)$id));
        return new News($stmt->fetch());
    }
    
    public function retrieveAll($newsID)
    {
        $newsID = (int)$newsID;
        $stmt = $this->db->prepare($this->statement->getRetrieveAllStatement());
        $stmt->execute(array(":news_id" => $newsID));
        return new NewsIterator($stmt);
    }
    
    public function insert(Comment $comment)
    {
        $stmt = $this->db->prepare($this->statement->getInsertStatement());
        return $stmt->execute(array(
            ":news_id" => $comment->news_id,
            ":author" => $comment->author,
            ":content" => $comment->content,
            ":created" => $comment->created
        ));
    }
}

?>