<?php

class NewsAddController extends Controller
{
    public function execute(Context $context)
    {
        $form = new NewsForm("news/add");
        if($form->validate($context)) {
            $mapper = new NewsMapper($context->getConnection());
            $news = new News($context->getRequest()->post);
            $news->created = time();
            if($mapper->insert($news)) {
                $context->getResponse()->setRedirect(url("news/manage"));
            }
            else throw new DomainException("COULD NOT INSERT NEWS");
        } else {
            return $form;
        }
    }
}

?>