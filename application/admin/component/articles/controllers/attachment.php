<?php
class ComArticlesControllerAttachment extends ComAttachmentsControllerAttachment
{
    public function __construct(KConfig $config)
    {
        parent::__construct($config);

        $this->getModel()->getTable()->attachBehavior('com://admin/articles.database.behavior.assignable');
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

    protected function _actionDelete(KCommandContext $context)
    {
        $entity = parent::_actionDelete($context);

        $context->response->setRedirect($context->request->getReferrer());

        return $entity;
    }
}