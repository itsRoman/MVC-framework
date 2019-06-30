<?php

class NewsAddView extends View
{
    public function __construct()
    {
        parent::__construct("newsadd");
    }
    
    public function execute(Context $context)
    {
        $this->attach(new NewsForm("news/add"));    
    }
}

?>