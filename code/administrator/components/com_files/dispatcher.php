<?php
/**
 * @version     $Id$
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Files
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Dispatcher Class
 *
 * @author      Ercan Ozkaya <http://nooku.assembla.com/profile/ercanozkaya>
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Files   
 */

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