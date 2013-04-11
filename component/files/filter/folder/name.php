<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git
 */

namespace Nooku\Component\Files;

use Nooku\Library;

/**
 * Folder Name Filter
 *
 * @author  Ercan Ozkaya <http://nooku.assembla.com/profile/ercanozkaya>
 * @package Nooku\Component\Files
 */
class FilterFolderName extends Library\FilterAbstract
{
	protected $_walk = false;

	protected function _validate($context)
	{
		$value = $context->getSubject()->name;

		if (strpos($value, '/') !== false) {
			$context->setError(\JText::_('Folder names cannot contain slashes'));
			return false;
		}

		if ($this->_sanitize($value) == '') {
			$context->setError(\JText::_('Invalid folder name'));
			return false;
		}
	}

	protected function _sanitize($value)
	{
		$value = str_replace('/', '', $value);
		return $this->getService('com:files.filter.path')->sanitize($value);
	}
}