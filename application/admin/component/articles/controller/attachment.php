<?php

use Nooku\Library;

class ArticlesControllerAttachment extends AttachmentsControllerAttachment
{
    public function __construct(Library\ObjectConfig $config)
    {
        parent::__construct($config);

        $this->getModel()->getTable()->attachBehavior('com:articles.database.behavior.assignable');

        $this->registerCallback(array('after.edit', 'after.delete'), array($this, 'setRedirect'));
    }

    protected function _initialize(Library\ObjectConfig $config)
    {
        $config->append(array(
            'model'   => 'com:attachments.model.attachments',
            'request' => array(
                'view' => 'attachment'
            )
        ));

        parent::_initialize($config);
    }

    public function setRedirect(Library\CommandContext $context)
    {
        $context->response->setRedirect($context->request->getReferrer());
    }
}