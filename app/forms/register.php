<?php

class RegisterForm extends Form
{    
    public function __construct($action)
    {
        parent::__construct("registerform", $action);
    }

    protected function describe()
    {
        $name = new Input("name");
        $name->label = "Username";
        $name->addRule(new NotEmptyRule());
        $email = new Input("email");
        $email->label = "Email";
        $email->addRule(new NotEmptyRule());
        $email->addRule(new IsEmailRule());
        $pw = new Input("pw");
        $pw->label = "Passwort";
        $pw->type = "password";
        $pw->addRule(new NotEmptyRule());
        $pw->addRule(new HasDefinedLengthRule(5, 15));
        $pwr = new Input("pwr");
        $pwr->label = "Passwort (wiederholen)";
        $pwr->type = "password";
        $pwr->addRule(new NotEmptyRule());
        $pwr->addRule(new HasDefinedLengthRule(5, 15));
        $pwr->addRule(new EqualsRule($pw));
        $submit = new Submit();#
        $submit->register(new OnClickEvent(new AjaxRequestAction($this->action, "post", $this)));
        $this->attach($name);
        $this->attach($email);
        $this->attach($pw);
        $this->attach($pwr);
        $this->attach($submit);
    }
}

?>