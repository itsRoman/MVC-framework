<?php

class UserLoginView extends View
{
    public function __construct()
    {
        parent::__construct("login");
    }
    
    public function execute(Context $context)
    {
        $loginform = new LoginForm("login");
        $this->attach($loginform);
    }
}

?>