<?php

use Nooku\Framework;

class ArticlesControllerAttachment extends AttachmentsControllerAttachment
{
    public function __construct(Framework\Config $config)
    {
        parent::__construct($config);

        $this->getModel()->getTable()->attachBehavior('com:articles.database.behavior.assignable');

        $this->registerCallback(array('after.edit', 'after.delete'), array($this, 'setRedirect'));
    }

    protected function _initialize(Framework\Config $config)
    {
        $config->append(array(
            'model'   => 'com:attachments.model.attachments',
            'request' => array(
                'view' => 'attachment'
            )
        ));

        parent::_initialize($config);
    }

    public function setRedirect(Framework\CommandContext $context)
    {
        $context->response->setRedirect($context->request->getReferrer());
    }
}