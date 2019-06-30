<?php

class UserInsertController extends Controller
{
    public function execute(Context $context)
    {
        $form = new RegisterForm("register");
        // noch unique username check implementieren
        if($form->validate($context)) {
            $mapper = new UserMapper($context->getConnection());
            $user = new User($context->getRequest()->post);
            $user->registered = time();
            if($mapper->insert($user)) {
                $context->getResponse()->setRedirect(url("profile/{$user->name}"));
            }
            else throw new DomainException("COULD NOT INSERT USER");
        } else {
            return $form;
        }
    }
}

?>