<?php

class Parameters extends Dataspace implements IAcceptance
{
    public function __construct($parameters = array())
    {
        parent::__construct();
        if($parameters instanceof Dataspace) $parameters = $parameters->export();
        if(!is_array($parameters)) throw new InvalidArgumentException("Array or Dataspace expected");
        foreach($parameters as $key => $value) {
            $this->set($key, $value);
        }
    }
    
    public function accept(IAcceptee $acceptee)
    {
        $acceptee->push($this);
    }
    
    public function escape($parameter, $encoding = "UTF-8")
    {
        return htmlentities($this->get($parameter), ENT_QUOTES, $encoding);
    }
} 


?>