<?php

abstract class DomainObject
{
    protected $internalParser;
    
    public function __construct($values = array())
    {
        if($values instanceof IDataspace) $values = $values->export();
        if(!is_array($values)) throw new UnexpectedValueException("Array expected");
        if(count($values) > 0) {
            $parser = new AnnotationDomainObjectParser($this);
            foreach($this->specify() as $property) {
                $mapped = $parser->mapField($property);
                if(isset($values[$mapped])) {
                    $this->$property = $values[$mapped];
                }
            }
        }
    }
    
    public function validate()
    {
        $parser = new AnnotationDomainObjectParser($this);
        foreach($this->specify() as $property) {
            $rules = $parser->getValidationRules($property);
            debug($rules, true);
        }
    }
    
    public function specify()
    {
        return array_keys(get_object_vars($this));
    }
 
    public function __set($name, $value)
    {
        if(in_array($name, $this->specify())) {
            $this->$name = $value;
        }
    }
    
    public function __get($name)
    {
        if(isset($this->$name)) {
            return $this->$name;
        }
        return null;
    }
    
    public function __isset($name)
    {
        return isset($this->$name);
    }
}

?>