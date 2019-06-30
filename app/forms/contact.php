<?php

class ContactForm extends Form
{    
    public function __construct($action)
    {
        parent::__construct("contactform", $action);
    }

    protected function describe()
    {
        $name = new Input("author");
        $name->label = "Name";
        $name->addRule(new NotEmptyRule());
        $comment = new Textarea("content"); 
        $comment->label = "Kommentar";
        $comment->addRule(new NotEmptyRule());
        $submit = new Submit();
        $submit->register(new OnClickEvent(new AjaxRequestAction($this->action, "post", $this)));
        $this->attach($name);
        $this->attach($comment);
        $this->attach($submit);
    }
}

?>