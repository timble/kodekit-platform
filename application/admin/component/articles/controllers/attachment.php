<?php
class ComArticlesControllerAttachment extends ComAttachmentsControllerAttachment
{
    public function __construct(KConfig $config)
    {
        parent::__construct($config);

        $this->getModel()->getTable()->attachBehavior('com://admin/articles.database.behavior.assignable');

        $this->registerCallback(array('after.edit', 'after.delete'), array($this, 'setRedirect'));
    }

    protected function _initialize(KConfig $config)
    {
        $config->append(array(
            'model'   => 'com://admin/attachments.model.attachments',
            'request' => array(
                'view' => 'attachment'
            )
        ));

        parent::_initialize($config);
    }

    public function setRedirect(KCommandContext $context)
    {
        $context->response->setRedirect($context->request->getReferrer());
    }
}