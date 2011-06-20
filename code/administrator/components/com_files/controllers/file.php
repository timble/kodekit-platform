<?php

class ComFilesControllerFile extends ComFilesControllerNode
{
	public function __construct(KConfig $config)
	{
		parent::__construct($config);

		$this->registerCallback('before.add', array($this, 'beforeAdd'));
	}

	public function beforeAdd(KCommandContext $context)
	{
		if (!$context->data->file) {
			$context->data->file = KRequest::get('files.file.tmp_name', 'raw');
			$context->data->path = KRequest::get('files.file.name', 'lib.koowa.filter.filename');
		}
	}

	/**
	 * We need to override this method because classic uploader would go to view=file otherwise
	 */
	public function setMessage(KCommandContext $context)
	{
		parent::setMessage($context);

		$this->_redirect = KRequest::referrer();
	}
}
