<?php

class GenericGateway implements IGateway
{
    protected $db;
    protected $result;
    
    public function __construct(IDbConnection $db)
    {
        $this->db = $db;
        $this->result = null;
    }
    
    public function retrieve()
    {
        $parser = new AnnotationDomainObjectParser($do);
        $query = "SELECT * FROM ";
        $query .= $this->db->quoteName($parser->getTable());
        $where = array();
        foreach($do->specify() as $field) {
            if(isset($do->$field)) {
                $where[] = $this->db->quoteName($parser->mapField($field)) . " = " . $this->db->quote($do->$field);
            }
        }
        if(count($where)) {
            $query .= " WHERE " . implode(" AND ", $where);
        }
        $domain = $parser->getClass();
        if(!$this->result) $this->result = $this->db->execute($sql);
        $row = $this->result->fetch();
        if(!$row) {
            $this->result->closeCursor();
            $this->result = null;
            return false;
        }
        return new $domain($row);
    }
    
    public function save(DomainObject $do)
    {
        $parser = new AnnotationDomainObjectParser($do);
        if(isset($do->{$parser->getPk()})) {
            return $this->update($do);
        } 
        return $this->insert($do);
    }
    
    public function insert(DomainObject $do)
    {
        $parser = new AnnotationDomainObjectParser($do);
        $query = "INSERT INTO ";
        $query .= $this->db->quoteName($parser->getTable());
        $fields = array();
        $values = array();
        foreach($do->specify() as $field) {
            if(isset($do->$field)) {
                $fields[] = $this->db->quoteName($parser->mapField($field));
                $values[] = $this->db->quote($do->$field);
            }
        }
        $query .= " (" . implode (", ", $fields) . ")";
        $query .= " VALUES (" . implode (", ", $values) . ")";
        return $this->db->execute($query);
    }
    
    public function update(DomainObject $do)
    {
        $parser = new AnnotationDomainObjectParser($do);
        $query = "UPDATE ";
        $query .= $this->db->quoteName($parser->getTable());
        $values = array();
        foreach($do->specify() as $field) {
            if(isset($do->$field)) {
                $values[] = $this->db->quoteName($field) . " = " . $this->db->quote($do->$field);
            }
        }
        $query .= " SET " . implode(", ", $values);
        $query .= " WHERE " . $this->db->quoteName($parser->getPk()) . " = " . $this->db->quote($do->{$parser->getPk()});
        return $this->db->execute($query);
    }
    
    public function delete(DomainObject $do)
    {
        $parser = new AnnotationDomainObjectParser($do);
        $query = "DELETE FROM ";
        $query .= $this->db->quoteName($parser->getTable());
        $query .= " WHERE " . $this->db->quoteName($parser->getPk()) . " = " . $this->db->quote($do->{$parser->getPk()});
        return $this->db->execute($query);
    }
}

?>
