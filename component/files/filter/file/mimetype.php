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
 * File Mimetype Filter
 *
 * @author  Ercan Ozkaya <http://github.com/ercanozkaya>
 * @package Kodekit\Component\Files
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
				return $this->addError($this->getObject('translator')->translate('Invalid Mimetype'));
			}
		}
	}
}