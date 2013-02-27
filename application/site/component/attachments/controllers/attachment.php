<?php
class ComAttachmentsControllerAttachment extends ComDefaultControllerResource
{
	protected function _initialize(KConfig $config)
	{
		$config->append(array(
		    'model'   => 'com://admin/attachments.model.attachments',
			'request' => array(
				'view' => 'attachment'
			)
		));
		
		parent::_initialize($config);
		
		$config->view = 'com://admin/attachments.view.'.$config->request->view.'.'.$config->request->format;
	}
}