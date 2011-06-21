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
 * File Exist Filter Class
 *
 * @author      Ercan Ozkaya <http://nooku.assembla.com/profile/ercanozkaya>
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Files   
 */

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