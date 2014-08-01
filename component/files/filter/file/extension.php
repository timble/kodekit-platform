<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

namespace Nooku\Component\Files;

use Nooku\Library;

/**
 * File Extension Filter
 *
 * @author  Ercan Ozkaya <http://nooku.assembla.com/profile/ercanozkaya>
 * @package Nooku\Component\Files
 */
class FilterFileExtension extends Library\FilterAbstract
{
	public function validate($entity)
	{
		$allowed = $entity->getContainer()->getParameters()->allowed_extensions;
		$value   = $entity->extension;

		if (is_array($allowed) && (empty($value) || !in_array(strtolower($value), $allowed))) {
			return $this->_error(\JText::_('Invalid file extension'));
		}
	}
}