<?php

class ComFilesFilterFileUploadable extends KFilterAbstract
{
	protected $_walk = false;

	public function __construct(KConfig $config)
	{
		parent::__construct($config);

		$this->_chain->enqueue(KFactory::tmp('admin::com.files.filter.file.name'), KCommand::PRIORITY_HIGH);
		$this->_chain->enqueue(KFactory::tmp('admin::com.files.filter.file.exists'), KCommand::PRIORITY_HIGH);

		$this->_chain->enqueue(KFactory::tmp('admin::com.files.filter.file.extension'));
		$this->_chain->enqueue(KFactory::tmp('admin::com.files.filter.file.mimetype'));
		$this->_chain->enqueue(KFactory::tmp('admin::com.files.filter.file.size'));
	}

	protected function _validate($context)
	{
	}

	protected function _sanitize($context)
	{

	}
}
