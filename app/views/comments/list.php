<?php

class CommentsListView extends View
{
    public function __construct()
    {
        parent::__construct("comments");
    }
    
    public function execute(Context $context)
    {
        $newsID = (int)$context->getRequest()->get->id;
	if(empty($newsID)) return $this->delegate("NewsList", $context);
        $mapper = new CommentMapper($context->getConnection());
        $this->result = $mapper->retrieveAll($newsID);
        $form = new CommentsForm("comments/insert");
        $form->fill(array("news_id" => $newsID));
        $this->attach($form);
    }
}

?>
