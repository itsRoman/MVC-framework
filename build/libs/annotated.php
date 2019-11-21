<?php

class Annotated
{
    protected $reflection;
    
    public function __construct($reflection)
    {
        if(!is_object($reflection) || !method_exists($reflection, "getDocComment")) throw new InvalidArgumentException("Passed reflection does not implement getDocComment().");
        $this->reflection = $reflection;
    }
    
    public function getAnnotation($tag)
    {
        $doc = $this->reflection->getDocComment();
        if(preg_match("#@{$tag}:?(.*)(\\r\\n|\\r|\\n)#", $doc, $matches)) { // TODO: mb ungreedy (U)?
            if(isset($matches[1]))
            {
                return trim($matches[1]);
            }
        }
        return false;
    }
}

?>