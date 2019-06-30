<?php

class NewsFullView extends View
{
    public function __construct()
    {
        parent::__construct("news");
    }
    
    public function execute(Context $context)
    {
        $id = (int)$context->getRequest()->get->id;
        // TODO: is this check rly necessary or already performed in the router?
        // if rly necessary, add this to all othe views
        if(empty($id)) return $this->delegate("NewsList", $context);
        $mapper = new NewsMapper($context->getConnection());
        $this->news = $mapper->retrieveByID($id);
        $this->news->id = $id;
        $this->attach(new CommentsListView());
    }
}

?>