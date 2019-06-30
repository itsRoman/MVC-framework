<?php

class NewsDeleteController extends Controller
{
    public function execute(Context $context)
    {
        $id = $context->getRequest()->get->id;
        $mapper = new NewsMapper($context->getConnection());
        if($mapper->deleteByID($id)) {
            $context->getResponse()->setRedirect(url("news/manage"));
        } else throw new DomainException("COULD NOT DELETE NEWS");
    }
}

?>