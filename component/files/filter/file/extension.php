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
 * File Extension Filter
 *
 * @author  Ercan Ozkaya <http://nooku.assembla.com/profile/ercanozkaya>
 * @package Nooku\Component\Files
 */
class FilterFileExtension extends Library\FilterAbstract
{
	protected $_walk = false;

	protected function _validate($context)
	{
		$allowed = $context->getSubject()->container->parameters->allowed_extensions;
		$value   = $context->getSubject()->extension;

		if (is_array($allowed) && (empty($value) || !in_array(strtolower($value), $allowed))) {
			$context->setError(\JText::_('Invalid file extension'));
			return false;
		}
	}

	protected function _sanitize($value)
	{
		return false;
	}
}