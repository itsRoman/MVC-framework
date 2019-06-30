<?php

class RootView extends View
{
    public function __construct()
    {
        parent::__construct("root");
    }
    
    public function execute(Context $context)
    {
	switch($context->getRequest()->get->module) {
	    case "news":
		$this->title = "Neuigkeiten - www.romanwilhelm.de";
		break;
	    case "aboutme":
		$this->title = "Über mich - www.romanwilhelm.de";
		break;
	    case "project":
		$this->title = "Projekte - www.romanwilhelm.de";
		break;
	    case "contact":
		$this->title = "Kontakt -  www.romanwilhelm.de";
		break;
	    case "impressum":
		$this->title = "Impressum - www.romanwilhelm.de";
		break;
	    default:
		$this->title = "Willkommen auf www.romanwilhelm.de";
	}
	$authenticated = $context->getSession()->authenticated;
	if(isset($authenticated) && $authenticated === true) {
	    $this->authenticated = true;
	    $this->attach(new UserAreaView());
	}
    }
}

?>