<?php
/**
 * @package     Nooku_Components
 * @subpackage  Files
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

use Nooku\Framework;

/**
 * File Mimetype Filter Class
 *
 * @author      Ercan Ozkaya <http://nooku.assembla.com/profile/ercanozkaya>
 * @package     Nooku_Components
 * @subpackage  Files
 */

class ComFilesFilterFileMimetype extends Framework\FilterAbstract
{
	protected $_walk = false;

	protected function _validate($context)
	{
		$row = $context->getSubject();
		$mimetypes = Framework\Config::unbox($row->container->parameters->allowed_mimetypes);

		if (is_array($mimetypes))
		{
			$mimetype = $row->mimetype;

			if (empty($mimetype))
            {
				if (is_uploaded_file($row->file) && $row->isImage()) {
					$info = getimagesize($row->file);
					$mimetype = $info ? $info['mime'] : false;
				} elseif ($row->file instanceof SplFileInfo) {
					$mimetype = $this->getService('com://admin/files.mixin.mimetype')->getMimetype($row->file->getPathname());
				}
			}

			if ($mimetype && !in_array($mimetype, $mimetypes)) {
				$context->setError(JText::_('Invalid Mimetype'));
				return false;
			}
		}
	}

	protected function _sanitize($value)
	{
		return false;
	}
}