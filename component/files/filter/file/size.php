<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

namespace Nooku\Component\Files;

use Nooku\Library;

/**
 * File Size Filter
 *
 * @author  Ercan Ozkaya <http://nooku.assembla.com/profile/ercanozkaya>
 * @package Nooku\Component\Files
 */
class FilterFileSize extends Library\FilterAbstract
{
	public function validate($row)
	{
		$max = $row->getContainer()->parameters->maximum_size;

		if ($max)
		{
			$size = $row->contents ? strlen($row->contents) : false;

			if (!$size && is_uploaded_file($row->file)) {
				$size = filesize($row->file);
			} elseif ($row->file instanceof \SplFileInfo && $row->file->isFile()) {
				$size = $row->file->getSize();
			}

			if ($size && $size > $max) {
				return $this->_error(\JText::_('File is too big'));
			}
		}
	}
}
