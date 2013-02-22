<?php

class ComAttachmentsControllerAttachment extends ComDefaultControllerDefault
{
	protected function _initialize(KConfig $config)
	{
		$config->append(array(
			'request' => array(
				'view' => 'attachments'
			)
		));
		
		parent::_initialize($config);
	}
}