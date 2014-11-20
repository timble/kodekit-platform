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
 * Folder Name Filter
 *
 * @author  Ercan Ozkaya <http://github.com/ercanozkaya>
 * @package Nooku\Component\Files
 */
class FilterFolderName extends Library\FilterAbstract
{
	public function validate($entity)
	{
		$value = $entity->name;

        $translator = $this->getObject('translator');

		if (strpos($value, '/') !== false) {
			return $this->_error($translator('Folder names cannot contain slashes'));
		}

		if ($this->sanitize($value) == '') {
			return $this->_error($translator('Invalid folder name'));
		}
	}

    public function sanitize($value)
	{
		$value = str_replace('/', '', $value);
		return $this->getObject('com:files.filter.path')->sanitize($value);
	}
}