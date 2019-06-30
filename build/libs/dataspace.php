<?php

abstract class Dataspace implements IDataspace, IteratorAggregate, Countable
{
    protected $vars = array(); // why not in constructor? think about it again :-)
    
    public function __construct() 
    {
        
    }
    
    public function set($key, $value)
    {
        $this->vars[$key] = $value;
    }
    
    public function get($key)
    {
        if($this->has($key)) return $this->vars[$key];
        else return null;
    }
    
    public function has($key)
    {
        if(array_key_exists($key, $this->vars)) return true;
        else return false;
    }
    
    public function import(IDataspace $dataspace)
    {
        $this->vars = array_merge($this->vars, $dataspace->export());
    }
    
    public function export()
    {
        return $this->vars;
    }
    
    public function __set($key, $value)
    {
        $this->set($key, $value);
    }
    
    public function __get($key)
    {
        return $this->get($key);
    }
    
    public function __isset($key)
    {
        return $this->has($key);
    }
    
    public function __unset($key)
    {
        if($this->has($key)) {
            unset($this->vars[$key]);
            return true;
        }
        return false;
    }
    
    public function flush()
    {
        $this->vars = array();
    }
    
    public function getIterator()
    {
        return new ArrayIterator($this->vars);
    }
    
    public function count()
    {
        return count($this->vars);
    }
}

?>