<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Component\Files;

use Nooku\Library;

/**
 * File Name Filter
 *
 * @author  Ercan Ozkaya <http://github.com/ercanozkaya>
 * @package Nooku\Component\Files
 */
class FilterFileName extends Library\FilterAbstract
{
	public function validate($entity)
	{
		$value = $this->sanitize($entity->name);

		if ($value == '') {
			return $this->_error($this->getObject('translator')->translate('Invalid file name'));
		}
	}

    public function sanitize($value)
	{
		return $this->getObject('com:files.filter.path')->sanitize($value);
	}
}