<?php

class ContactShowView extends View
{
    public function __construct()
    {
        parent::__construct("contact");
    }
    
    public function execute(Context $context)
    {
        $form = new ContactForm("contact/insert");
        $this->attach($form);
    }
}

?>