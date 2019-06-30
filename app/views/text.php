<?php

class TextView extends View
{
    public function __construct($text = "")
    {
        parent::__construct("text");
        $this->text = $text;
    }
    
    public function execute(Context $context)
    {

    }
}

?>