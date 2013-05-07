<?php

use Nooku\Library;

class AttachmentsControllerAttachment extends ApplicationControllerDefault
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