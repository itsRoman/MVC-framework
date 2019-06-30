<?php

class UserLogoutController extends Controller
{
    public function execute(Context $context)
    {
        $session = $context->getSession();
        if(isset($session->authenticated)) {
            $session->username = null;
            $session->authenticated = false;
            $context->getResponse()->setRedirect("news");
        }
    }
}

?>