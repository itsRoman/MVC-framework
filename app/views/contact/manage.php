<?php

class ContactManageView extends View
{
    public function __construct()
    {
        parent::__construct("contactmanage");
    }
    
    public function execute(Context $context)
    {
        $mapper = new ContactMapper($context->getConnection());
        $this->result = $mapper->retrieveAll();
    }
}

?>