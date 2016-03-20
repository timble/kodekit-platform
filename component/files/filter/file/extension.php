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
 * File Extension Filter
 *
 * @author  Ercan Ozkaya <http://github.com/ercanozkaya>
 * @package Kodekit\Component\Files
 */
class FilterFileExtension extends Library\FilterAbstract
{
	public function validate($entity)
	{
		$allowed = Library\ObjectConfig::unbox($entity->getContainer()->getParameters()->allowed_extensions);
		$value   = $entity->extension;

		if (is_array($allowed) && (empty($value) || !in_array(strtolower($value), $allowed))) {
			return $this->addError($this->getObject('translator')->translate('Invalid file extension'));
		}
	}
}