<?php

use Nooku\Framework;

class ComAttachmentsControllerAttachment extends ComBaseControllerDefault
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