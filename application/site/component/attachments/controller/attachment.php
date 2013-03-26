<?php

use Nooku\Framework;

class AttachmentsControllerAttachment extends ApplicationControllerDefault
{
	protected function _initialize(Framework\Config $config)
	{
		$config->append(array(
		    'model'   => 'com:attachments.model.attachments',
			'request' => array(
				'view' => 'attachment'
			)
		));
		
		parent::_initialize($config);
		
		$config->view = 'com:attachments.view.'.$config->request->view.'.'.$config->request->format;
	}
}