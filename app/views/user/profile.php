<?php

class UserProfileView extends View
{
    public function __construct()
    {
        parent::__construct("profile");
    }
    
    public function execute(Context $context)
    {
        $id = $context->getRequest()->get->id;
        if($authed = $context->getSession()->authenticated || !empty($id)) {
            if(empty($id)) $id = $context->getSession()->username;
            if(empty($id)) throw new LogicException("No username found");
            $this->authenticated = $authed;
            $this->id = $id;
            $mapper = new UserMapper($context->getConnection());
            $this->user = $mapper->retrieveByName($id);
        } else {
            // fehlermeldung
        }
    }
}

?>