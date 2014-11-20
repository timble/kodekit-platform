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
 * File Mimetype Filter
 *
 * @author  Ercan Ozkaya <http://github.com/ercanozkaya>
 * @package Nooku\Component\Files
 */
class FilterFileMimetype extends Library\FilterAbstract
{
	public function validate($entity)
	{
		$mimetypes = Library\ObjectConfig::unbox($entity->getContainer()->getParameters()->allowed_mimetypes);

		if (is_array($mimetypes))
		{
			$mimetype = $entity->mimetype;

			if (empty($mimetype))
            {
				if (is_uploaded_file($entity->file) && $entity->isImage())
                {
					$info = getimagesize($entity->file);
					$mimetype = $info ? $info['mime'] : false;
				}
                elseif ($entity->file instanceof SplFileInfo) {
					$mimetype = $this->getObject('com:files.mixin.mimetype')->getMimetype($entity->file->getPathname());
				}
			}

			if ($mimetype && !in_array($mimetype, $mimetypes)) {
				return $this->_error($this->getObject('translator')->translate('Invalid Mimetype'));
			}
		}
	}
}