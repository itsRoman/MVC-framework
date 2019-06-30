<?php

/**
 * Base class for all mappers
 * Provides basic properties
 *
 * @author Roman Wilhelm <nospam@romanwilhelm.de>
 * @todo Check interface IMapper, really neccessary ? 
 */
abstract class GenericMapper implements IMapper
{
    protected $db;
    protected $statement;
    
    public function __construct(PDO $db, IStatement $statement)
    {
        $this->db = $db;
        $this->statement = $statement;
    }
}

/**
 * Lazy Iterator for a resultset
 *
 * @author Roman Wilhelm <nospam@romanwilhelm.de>
 */
class LazyResultsetIterator
{
    protected $rs;
    
    public function __construct($rs)
    {
        $this->rs = $rs;
    }
    
    /**
     * Fetches the next row of a resultset
     *
     * @author Roman Wilhelm <nospam@romanwilhelm.de>
     * @return array one row of the resultset or false if resultset is empty
     */
    public function next()
    {
        if($row = $this->rs->fetch()) return $row;
        $this->rs->closeCursor();
        return false;
    }
}

?>