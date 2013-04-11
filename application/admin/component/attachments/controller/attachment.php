<?php

use Nooku\Library;

class AttachmentsControllerAttachment extends ApplicationControllerDefault
{
	protected function _initialize(Library\Config $config)
	{
		$config->append(array(
			'request' => array(
				'view' => 'attachments'
			)
		));
		
		parent::_initialize($config);
	}
}