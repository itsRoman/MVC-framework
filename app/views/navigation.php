<?php

class NavigationView extends View
{
    public function __construct()
    {
        parent::__construct("navigation");
    }
    
    public function execute(Context $context)
    {
	$this->active = $context->getRequest()->get->module;
    }
}

?>