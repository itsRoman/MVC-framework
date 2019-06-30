<?php

class CommentsForm extends Form
{    
    public function __construct($action)
    {
        parent::__construct("commentsform", $action);
    }

    protected function describe()
    {
        $news_id = new Input("news_id");
        $news_id->type = "hidden";
        $name = new Input("author");
        $name->label = "Name";
        $name->addRule(new NotEmptyRule());
        $comment = new Textarea("content"); // mabye add some form_ in front of every element's id?
        $comment->label = "Kommentar";
        $comment->addRule(new NotEmptyRule());
        $submit = new Submit();
        $submit->register(new OnClickEvent(new AjaxRequestAction($this->action, "post", $this)));
        $this->attach($news_id);
        $this->attach($name);
        $this->attach($comment);
        $this->attach($submit);
    }
}

?>