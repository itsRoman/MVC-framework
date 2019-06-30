<?php

class NewsForm extends Form
{    
    public function __construct($action)
    {
        parent::__construct("newsform", $action);
    }

    protected function describe()
    {
        $headline = new Input("headline");
        $headline->label = "Überschrift";
        $headline->addRule(new NotEmptyRule());
        $content = new Textarea("content");
        $content->label = "Text";
        $content->addRule(new NotEmptyRule());
        $published = new Checkbox("published");
        $published->label = "Veröffentlichen?";
        $submit = new Submit();#
        $submit->register(new OnClickEvent(new AjaxRequestAction($this->action, "post", $this)));
        $this->attach($headline);
        $this->attach($content);
        $this->attach($published);
        $this->attach($submit);
    }
}

?>