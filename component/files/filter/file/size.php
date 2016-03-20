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
 * File Size Filter
 *
 * @author  Ercan Ozkaya <http://github.com/ercanozkaya>
 * @package Kodekit\Component\Files
 */
class FilterFileSize extends Library\FilterAbstract
{
	public function validate($entity)
	{
		$max = $entity->getContainer()->getParameters()->maximum_size;

		if ($max)
		{
			$size = $entity->contents ? strlen($entity->contents) : false;

			if (!$size && is_uploaded_file($entity->file)) {
				$size = filesize($entity->file);
			} elseif ($entity->file instanceof \SplFileInfo && $entity->file->isFile()) {
				$size = $entity->file->getSize();
			}

			if ($size && $size > $max) {
				return $this->addError($this->getObject('translate')->translate('File is too big'));
			}
		}
	}
}
