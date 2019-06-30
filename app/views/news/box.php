<?php

class NewsBoxView extends View
{
    public function __construct()
    {
        parent::__construct("newsbox");
    }
    
    public function execute(Context $context)
    {
        $mapper = new NewsMapper($context->getConnection());
        $this->result = $mapper->retrieveAll();
    }
}

?>