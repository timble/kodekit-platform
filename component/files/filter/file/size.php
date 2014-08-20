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
 * File Size Filter
 *
 * @author  Ercan Ozkaya <http://github.com/ercanozkaya>
 * @package Nooku\Component\Files
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
				return $this->_error($this->getObject('translate')->translate('File is too big'));
			}
		}
	}
}
