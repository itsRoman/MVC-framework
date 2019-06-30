<?php

class UserLoginController extends Controller
{
    public function execute(Context $context)
    {
        $form = new LoginForm("login");
        if($form->validate($context)) {
            $mapper = new UserMapper($context->getConnection());
            $user = new User($context->getRequest()->post);
            if($mapper->login($user)) {
                $session = $context->getSession();
                $session->username = $user->name;
                $session->authenticated = true;
                $context->getResponse()->setRedirect(url("profile/{$user->name}"));
            } else {
                $form->setError("Falsche Daten. Bitte nocheinmal versuchen.");
                $elements = $form->getChildren();
                $elements["pw"]->value = "";
                return $form;
            }
        } else {
            return $form;
        }
    }
}

?>