<?php

class EmptyView extends View
{
    public function __construct()
    {
        parent::__construct("empty");
    }
    
    public function execute(Context $context)
    {
    }
}

?>