<?php

class User extends Parameters
{

}

class UserIterator extends LazyResultsetIterator
{
    public function next()
    {
        if($row = parent::next()) return new User($row);
        return false;
    }
}

/**
 * User Mapper
 * Data access logic for persisting User objects
 *
 * @author Roman Wilhelm <nospam@romanwilhelm.de>
 * @todo Clean up naming conflicts, e.g. retrieveByName() </-> getByNameStatement()
 */
class UserMapper extends GenericMapper
{
    public function __construct(PDO $db)
    {
        parent::__construct($db, new UserStatement());
    }
    
    public function retrieveByName($name)
    {
        $stmt = $this->db->prepare($this->statement->getByNameStatement());
        $stmt->execute(array(":name" => $name));
        if(!$row = $stmt->fetch()) return false;
        return new User($row);
    }
    
    public function retrieveAll()
    {
        $stmt = $this->db->prepare($this->statement->getRetrieveAllStatement());
        $stmt->execute();
        return new UserIterator($stmt);
    }
    
    public function login(User $user)
    {
        $stmt = $this->db->prepare($this->statement->getLoginStatement());
        $stmt->execute(array(":name" => $user->name, ":pw" => $user->pw));
        if(!$row = $stmt->fetch()) return false;
        return true;
    }
    
    public function insert(User $user)
    {
        $stmt = $this->db->prepare($this->statement->getInsertStatement());
        return $stmt->execute(array(
                                   ":name" => $user->name,
                                   ":email" => $user->email,
                                   ":pw" => $user->pw,
                                   ":registered" => $user->registered
                                   ));
    }
}

?>