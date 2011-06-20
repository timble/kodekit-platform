<?php

class ComFilesFilterFolderUploadable extends KFilterAbstract
{
	protected $_walk = false;

	public function __construct(KConfig $config)
	{
		parent::__construct($config);

		$this->_chain->enqueue(KFactory::tmp('admin::com.files.filter.folder.name'), KCommand::PRIORITY_HIGH);
		$this->_chain->enqueue(KFactory::tmp('admin::com.files.filter.folder.exists'), KCommand::PRIORITY_HIGH);
	}

	protected function _validate($context)
	{
	}

	protected function _sanitize($context)
	{

	}
}
