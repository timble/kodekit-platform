<?php
KLoader::loadIdentifier('com://site/comments.aliases');

class ComCommentsControllerComment extends ComDefaultControllerDefault 
{
    public function __construct(Kconfig $config)
    {
        parent::__construct($config);
        
        $this->registerCallback('after.save', array($this, 'afterSave'));
    }
    
    protected function _initialize(KConfig $config)
    {
        $config->append(array(
            'model' => 'com://site/comments.model.comments'
        ));
        
        $config->request->created_by = JFactory::getUser()->id;
        
        parent::_initialize($config);
    }
    
    public function afterSave(KCommandContext $context)
    {
        $url = ($referrer = KRequest::get('post.referrer', 'base64')) ? base64_decode($referrer) : KRequest::referrer();
        $this->setRedirect($url);
    }
}