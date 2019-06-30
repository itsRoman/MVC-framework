<?php

class UserRegisterView extends View
{
    public function __construct()
    {
        parent::__construct("register");
    }
    
    public function execute(Context $context)
    {
        $registerform = new RegisterForm("register");
        $this->attach($registerform);
    }
}

?>