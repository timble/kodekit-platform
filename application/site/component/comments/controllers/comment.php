<?php

use Nooku\Framework;

class CommentsControllerComment extends BaseControllerDefault
{
    public function __construct(Framework\Config $config)
    {
        parent::__construct($config);
        
        $this->registerCallback('after.add', array($this, 'redirect'));
    }
    
    public function redirect(Framework\CommandContext $context)
    {
        $url = ($referrer = Framework\Request::get('post.referrer', 'base64')) ? base64_decode($referrer) : Framework\Request::referrer();
        $this->setRedirect($url);
    }
}