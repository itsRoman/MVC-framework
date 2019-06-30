<?php

class NewsEditController extends Controller
{
    public function execute(Context $context)
    {
        $id = $context->getRequest()->get->id;
        $form = new NewsForm("news/edit");
        if($form->validate($context)) {
            $mapper = new NewsMapper($context->getConnection());
            $news = new News($context->getRequest()->post);
            $news->id = $id;
            if($mapper->update($news)) {
                $context->getResponse()->setRedirect(url("news/manage"));
            }
            else throw new DomainException("COULD NOT UPDATE NEWS");
        } else {
            return $form;
        }
    }
}

?>