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
 * File Name Filter
 *
 * @author  Ercan Ozkaya <http://github.com/ercanozkaya>
 * @package Kodekit\Component\Files
 */
class FilterFileName extends Library\FilterAbstract
{
	public function validate($entity)
	{
		$value = $this->sanitize($entity->name);

		if ($value == '') {
			return $this->addError($this->getObject('translator')->translate('Invalid file name'));
		}
	}

    public function sanitize($value)
	{
		return $this->getObject('com:files.filter.path')->sanitize($value);
	}
}