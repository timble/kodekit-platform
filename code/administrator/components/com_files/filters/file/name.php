<?php

Kloader::load('lib.joomla.filesystem.file');

class ComFilesFilterFileName extends KFilterAbstract
{
	protected $_walk = false;

	protected function _validate($context)
	{
		$value = $this->_sanitize($context->caller->path);

		if ($value == '') {
			$context->setError(JText::_('WARNFILENAME'));
			return false;
		}
	}

	protected function _sanitize($value)
	{
		return JFile::makeSafe($value);
	}
}