<?php

interface IEvent
{
    public function getName();
    public function __toString();
}

class Event implements IEvent
{
    protected $action;
    
    public function __construct(IAction $action)
    {
        $this->action = $action;
    }
    
    public function getName()
    {
        return strtolower(substr(get_class($this), 0, -5));
    }
    
    public function __toString()
    {
        return ($this->getName() . "=\"" . $this->action . "\"");
    }
}

class OnClickEvent extends Event
{

}

?>