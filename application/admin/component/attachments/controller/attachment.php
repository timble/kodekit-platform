<?php

use Nooku\Library;

class AttachmentsControllerAttachment extends Library\ControllerModel
{
	protected function _initialize(Library\ObjectConfig $config)
	{
		$config->append(array(
			'request' => array(
				'view' => 'attachments'
			)
		));
		
		parent::_initialize($config);
	}
}