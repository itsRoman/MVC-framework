<?php

class ContactInsertController extends Controller
{
    public function execute(Context $context)
    {
        $form = new ContactForm("contact/insert");
        if($form->validate($context)) {
            $mapper = new ContactMapper($context->getConnection());
            $contact = new Contact($context->getRequest()->post);
            $contact->created = time();
            if($mapper->insert($contact)) {
                $successor = new TextView("Kontaktanfrage erfolgreich gespeichert.");
                $successor->setID("content");
                return $successor;
            }
            else throw new DomainException("COULD NOT INSERT CONTACT REQUEST");
        } else return $form;
    }
}

?>