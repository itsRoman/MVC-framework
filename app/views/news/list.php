<?php

class NewsListView extends View
{
    public function __construct()
    {
        parent::__construct("news");
    }
    
    public function execute(Context $context)
    {
        $mapper = new NewsMapper($context->getConnection());
        $this->result = $mapper->retrieveAllWithCommentsCount();
    }
}

?>