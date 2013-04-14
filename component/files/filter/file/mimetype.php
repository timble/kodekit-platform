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
 * File Mimetype Filter
 *
 * @author  Ercan Ozkaya <http://nooku.assembla.com/profile/ercanozkaya>
 * @package Nooku\Component\Files
 */
class FilterFileMimetype extends Library\FilterRecursive
{
	protected $_traverse = false;

	protected function _validate($context)
	{
		$row = $context->getSubject();
		$mimetypes = Library\Config::unbox($row->container->parameters->allowed_mimetypes);

		if (is_array($mimetypes))
		{
			$mimetype = $row->mimetype;

			if (empty($mimetype))
            {
				if (is_uploaded_file($row->file) && $row->isImage())
                {
					$info = getimagesize($row->file);
					$mimetype = $info ? $info['mime'] : false;
				}
                elseif ($row->file instanceof SplFileInfo) {
					$mimetype = $this->getService('com:files.mixin.mimetype')->getMimetype($row->file->getPathname());
				}
			}

			if ($mimetype && !in_array($mimetype, $mimetypes))
            {
				$context->setError(\JText::_('Invalid Mimetype'));
				return false;
			}
		}
	}

	protected function _sanitize($value)
	{
		return false;
	}
}