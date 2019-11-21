<?php

class DomainObject
{
    public function __construct(array $values)
    {
        foreach($values as $key => $value) {
            $setter = "set" . ucfirst($key);
            $this->{$setter}($value);
        }
    }
    
    public function __call($name, array $arguments)
    {
        if(preg_match("#^get([A-Z]{1}[a-zA-Z_])$#", $name, $matches)) {
            if(isset($this->{$matches[0]})) return $this->{$matches[0]};
            else return null;
        } elseif(!empty($arguments[0]) && preg_match("#^set([A-Z]{1}[a-zA-Z_])$#", $name, $matches)) {
            if(array_key_exists($matches[0], get_object_vars($this))) {
                $this->{$matches[0]} = $arguments[0];
            }
        }
    }
}

abstract class Record extends Dataspace implements IRecord
{
    protected $db;
    protected $statement;
    protected $result;
    protected $fields;
    protected $clauses;
    protected $orderby;
    protected $limitby;
    protected $groupby;
    
    public function __construct(IDbConnection $db, IStatement $statement)
    {
        parent::__construct();
        $this->db = $db;
        $this->statement = $statement;
        $this->result = null;
        $this->fields = array();
        $this->clauses = array();
        $this->orderby = null;
        $this->limitby = null;
        $this->groupby = null;
        $this->getColumns();
    }
    
    public function retrieve()
    {
        $sql = $this->statement->getSelectStatement();
        if(empty($this->clauses)) {
            if($this->has("id") && trim($this->get("id")) != "") {
                if(ctype_digit((string)$this->get("id"))) $this->where("id = " . (int)$this->get("id"));
                else $this->where("id = " . (string)$this->db->quote($this->get("id")));   
            }
        } 
        if(!empty($this->clauses)) $sql .= " WHERE ";
        foreach($this->clauses as $clause) {
            $sql .= $clause;  
        }
        if(isset($this->orderby)) $sql .= $this->orderby;
        if(isset($this->limitby)) $sql .= $this->limitby;
        if(isset($this->groupby)) $sql .= $this->groupby;
        if(!$this->result) $this->result = $this->db->execute($sql);
        $row = $this->result->fetch();
        if(!$row) {
            $this->result->closeCursor();
            $this->result = null;
            return false;
        }
        $this->import($row);
        return true;
    }
    
    public function customRetrieve($sql)
    {
        if(!$this->result) $this->result = $this->db->execute($sql);
        $row = $this->result->fetch();
        if(!$row) {
            $this->result->closeCursor();
            $this->result = null;
            return false;
        }
        $this->import($row);
        return true;
    }
    
    public function where($clause)
    {
        if(empty($this->clauses)) array_push($this->clauses, (string)$clause);
        else array_push($this->clauses, "and {$clause}");
    }
    
    public function orWhere($clause)
    {
        if(empty($this->clauses)) array_push($this->clauses, (string)$clause);
        else array_push($this->clauses, "or {$clause}");
    }

    public function orderBy($orderby)
    {
        $this->orderby = $orderby;
    }
    
    public function limitby($limitby)
    {
        $this->limitby = $limitby;
    }
    
    public function groupBy($groupby)
    {
        $this->groupby = $groupby;
    } 
    
    public function insert()
    {
        $values = array_values($this->quoteFields());
        if(in_array("id", $values) && !$this->has("id")) unset($this->id);
        $sql = vsprintf($this->statement->getInsertStatement(), $values);
        $result = $this->db->execute($sql);
        $this->id = $this->db->lastInsertID();
        return $result; 
    }
    
    public function update()
    {
        $values = array_values($this->quoteFields());
        $sql = vsprintf($this->statement->getUpdateStatement(), $values);
        return $this->db->execute($sql);
    }
    
    public function delete()
    {
        $sql = $this->statement->getDeleteStatement();
        if(empty($this->clauses)) {
            if($this->has($id)) {
                if(ctype_digit($this->get("id"))) $this->where("id = %d");
                else $this->where("id = %s");     
            }
        } else {
            $sql .= " WHERE ";
            foreach($this->clauses as $clause) {
                $sql .= $clause;
            }
        }
        return $this->db->execute($sql);
    }
    
    protected function quoteFields()
    {
        $temp = array();
        foreach($this->fields as $field) {
            $temp[$field] = isset($this->{$field}) ? $this->db->quote($this->{$field}) : null;
        }
        return $temp;
    }
    
    protected function getColumns()
    {
        $sql = $this->statement->getColumnsStatement();
        $result = $this->db->execute($sql);
        while($row = $result->fetch()) {
            array_push($this->fields, $row->field);
        }
    }
}

?>
