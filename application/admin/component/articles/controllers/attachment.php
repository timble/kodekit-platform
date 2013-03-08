<?php

use Nooku\Framework;

class ComArticlesControllerAttachment extends ComAttachmentsControllerAttachment
{
    public function __construct(Framework\Config $config)
    {
        parent::__construct($config);

        $this->getModel()->getTable()->attachBehavior('com://admin/articles.database.behavior.assignable');

        $this->registerCallback(array('after.edit', 'after.delete'), array($this, 'setRedirect'));
    }

    protected function _initialize(Framework\Config $config)
    {
        $config->append(array(
            'model'   => 'com://admin/attachments.model.attachments',
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