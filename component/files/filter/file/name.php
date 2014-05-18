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
 * File Name Filter
 *
 * @author  Ercan Ozkaya <http://nooku.assembla.com/profile/ercanozkaya>
 * @package Nooku\Component\Files
 */
class FilterFileName extends Library\FilterAbstract
{
	public function validate($row)
	{
		$value = $this->sanitize($row->name);

		if ($value == '') {
			return $this->_error(\JText::_('Invalid file name'));
		}
	}

    public function sanitize($value)
	{
		return $this->getObject('com:files.filter.path')->sanitize($value);
	}
}