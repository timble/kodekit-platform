<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
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
	public function validate($row)
	{
		$value = $row->name;

		if (strpos($value, '/') !== false) {
			return $this->_error(\JText::_('Folder names cannot contain slashes'));
		}

		if ($this->sanitize($value) == '') {
			return $this->_error(\JText::_('Invalid folder name'));
		}
	}

    public function sanitize($value)
	{
		$value = str_replace('/', '', $value);
		return $this->getObject('com:files.filter.path')->sanitize($value);
	}
}