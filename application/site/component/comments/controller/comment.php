<?php

use Nooku\Library;

class CommentsControllerComment extends ApplicationControllerDefault
{
    public function __construct(Library\ObjectConfig $config)
    {
        parent::__construct($config);
        
        $this->registerCallback('after.add', array($this, 'redirect'));
    }
    
    public function redirect(Library\CommandContext $context)
    {
        $url = ($referrer = Library\Request::get('post.referrer', 'base64')) ? base64_decode($referrer) : Library\Request::referrer();
        $this->setRedirect($url);
    }
}