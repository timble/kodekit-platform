<?php

class ComFilesFilterFileExtension extends KFilterFilename
{
	protected $_walk = false;

	protected $_config;

	public function __construct(KConfig $config)
	{
		parent::__construct($config);

		$this->_config = $config;
	}

	protected function _initialize(KConfig $config)
	{
		$component_config = KFactory::get('admin::com.files.database.row.config');

		$allowed = array_map('strtolower', $component_config->upload_extensions);
		$ignored = array_map('strtolower', $component_config->ignore_extensions);

		$config->append(array(
			'allowed' => $allowed,
			'ignored' => $ignored
		));

		parent::_initialize($config);
	}

	protected function _validate($context)
	{
		$config = $this->_config;
		$value = $context->caller->extension;

		if (empty($value) || (!in_array($value, $config->ignored->toArray()) && !in_array($value, $config->allowed->toArray()))) {
			$context->setError(JText::_('WARNFILETYPE'));
			return false;
		}
	}

	protected function _sanitize($value)
	{
		return false;
	}
}