<?php

use Nooku\Framework;

class AttachmentsControllerAttachment extends ApplicationControllerDefault
{
	protected function _initialize(Framework\Config $config)
	{
		$config->append(array(
			'request' => array(
				'view' => 'attachments'
			)
		));
		
		parent::_initialize($config);
	}
}