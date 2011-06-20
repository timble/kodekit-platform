<?php

class ComFilesDispatcher extends ComDefaultDispatcher
{
	/**
	 * Overloaded to comply with FancyUpload.
	 * It doesn't let us pass AJAX headers so this is needed.
	 */
	public function _actionForward(KCommandContext $context)
	{
		if(KRequest::type() == 'FLASH') {
			$context->result = $this->getController()->execute('display', $context);
		} else {
			parent::_actionForward($context);
		}

		return $context->result;

	}
}