<?php

class CommentsInsertController extends Controller
{
    public function execute(Context $context)
    {
        $form = new CommentsForm("comments/insert");
        if($form->validate($context)) {
            $mapper = new CommentMapper($context->getConnection());
            $comment = new Comment($context->getRequest()->post);
            $comment->created = time();
            if($mapper->insert($comment)) {
                $context->getResponse()->setRedirect(url("news/full/{$comment->news_id}"));
            }
            else throw new DomainException("COULD NOT INSERT COMMENT");
        } else {
            return $form;
        }
    }
}

?>