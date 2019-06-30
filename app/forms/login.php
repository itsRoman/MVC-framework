<?php

class LoginForm extends Form
{    
    public function __construct($action)
    {
        parent::__construct("loginform", $action);
    }

    protected function describe()
    {
        $name = new Input("name");
        $name->label = "Username";
        $name->addRule(new NotEmptyRule());
        $pw = new Input("pw");
        $pw->label = "Passwort";
        $pw->type = "password";
        $pw->addRule(new NotEmptyRule());
        $submit = new Submit();
        $submit->register(new OnClickEvent(new AjaxRequestAction($this->action, "post", $this)));
        $this->attach($name);
        $this->attach($pw);
        $this->attach($submit);
    }
}

?>