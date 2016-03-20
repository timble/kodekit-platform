<?php
/**
 * Kodekit Component - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-files for the canonical source repository
 */

namespace Kodekit\Component\Files;

use Kodekit\Library;

/**
 * Folder Name Filter
 *
 * @author  Ercan Ozkaya <http://github.com/ercanozkaya>
 * @package Kodekit\Component\Files
 */
class FilterFolderName extends Library\FilterAbstract
{
	public function validate($entity)
	{
		$value = $entity->name;

        $translator = $this->getObject('translator');

		if (strpos($value, '/') !== false) {
			return $this->addError($translator('Folder names cannot contain slashes'));
		}

		if ($this->sanitize($value) == '') {
			return $this->addError($translator('Invalid folder name'));
		}
	}

    public function sanitize($value)
	{
		$value = str_replace('/', '', $value);
		return $this->getObject('com:files.filter.path')->sanitize($value);
	}
}