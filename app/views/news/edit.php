<?php

class NewsEditView extends View
{
    public function __construct()
    {
        parent::__construct("newsedit");
    }
    
    public function execute(Context $context)
    {
        $id = $context->getRequest()->get->id;
        $mapper = new NewsMapper($context->getConnection());
        $this->news = $mapper->retrieveByID($id);
        $form = new NewsForm("news/edit/{$id}");
        $form->fill($this->news->export());
        $this->attach($form);
    }
}

?>