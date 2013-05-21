<?php

use Nooku\Library;

class AttachmentsControllerAttachment extends Library\ControllerModel
{
	protected function _initialize(Library\ObjectConfig $config)
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