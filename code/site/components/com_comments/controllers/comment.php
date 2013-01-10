<?php
KLoader::loadIdentifier('com://site/comments.aliases');

class ComCommentsControllerComment extends ComDefaultControllerDefault 
{
    public function __construct(Kconfig $config)
    {
        parent::__construct($config);
        
        $this->registerCallback('after.add', array($this, 'redirect'));
    }
    
    public function redirect(KCommandContext $context)
    {
        $url = ($referrer = KRequest::get('post.referrer', 'base64')) ? base64_decode($referrer) : KRequest::referrer();
        $this->setRedirect($url);
    }
}