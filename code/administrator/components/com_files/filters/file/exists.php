<?php

class ComFilesFilterFileExists extends KFilterAbstract
{
	protected $_walk = false;

	protected function _validate($context)
	{
		$row = $context->caller;

		if (!$row->isNew()) {
			$context->setError(JText::_('Error. File already exists'));
			return false;
		}
	}

	protected function _sanitize($context)
	{
		return false;
	}
}