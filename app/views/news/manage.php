<?php

class NewsManageView extends View
{
    public function __construct()
    {
        parent::__construct("newsmanage");
    }
    
    public function execute(Context $context)
    {
        $mapper = new NewsMapper($context->getConnection());
        $this->result = $mapper->retrieveAllWithCommentsCount();
    }
}

?>