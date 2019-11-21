<?php

// TODO: add sth like getRelation($field) or translateRelation($field) ..

class AnnotationDomainObjectParser implements IDomainObjectParser
{
    protected $do;
    protected $class;
    protected $table;
    protected $pk;
    protected $map;
    protected $rules;
    
    public function __construct(DomainObject $do)
    {
        $this->do = $do;
        $this->map = array();
        $this->rules = array();
    }
    
    public function getClass()
    {
        if(!isset($this->class)) {
            $this->class = get_class($this->do);
        }
        return $this->class;      
    }
    
    public function getTable()
    {
        if(!isset($this->table)) {
            $annotated = new Annotated(new ReflectionClass($this->do));
            if(!($table = $annotated->getAnnotation("table"))) {
                $table = $this->getClass($this->do);
            }
            $this->table = strtolower($table);
        }
        return $this->table;   
    }
    
    public function getPk()
    {
        if(!isset($this->pk)) {
            foreach($this->do->specify() as $field) {
                $annotated = new Annotated(new ReflectionProperty($this->do, $field));
                if($annotated->getAnnotation("pk") !== false) {
                    $this->pk = $field;
                    break;
                }
            }
        }
        return $this->pk;    
    }
    
    public function mapField($field)
    {
        if(!isset($this->map[$field])) {
            $annotated = new Annotated(new ReflectionProperty($this->do, $field));
            $f = $annotated->getAnnotation("maps");
            $this->map[$field] = isset($f) ? $f : $field;
        }
        return $this->map[$field];
    }
    
    public function getValidationRules($field)
    {
        if(!isset($this->rules[$field])) {
            $annotated = new Annotated(new ReflectionProperty($this->do, $field));
            $rules = $annotated->getAnnotation("validate");
            $this->rules[$field] = $rules;
        }
        return $this->rules[$field];
    }
}

?>