<?php
/**
 * @version     $Id$
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Files
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Filesize Helper Class
 *
 * @author      Ercan Ozkaya <http://nooku.assembla.com/profile/ercanozkaya>
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Files   
 */

class ComFilesTemplateHelperFilesize extends KTemplateHelperAbstract
{
	public function humanize($config = array())
	{
		$config = new KConfig($config);
		$config->append(array(
			'sizes' => array('Bytes', 'Kb', 'Mb', 'Gb', 'Tb', 'Pb')
		));
		$bytes = $config->size;
		$result = '';
		$format = (($bytes > 1024*1024 && $bytes % 1024 !== 0) ? '%.2f' : '%d').' %s';

		foreach ($config->sizes as $s) {
			$size = $s;
			if ($bytes < 1024) {
				$result = $bytes;
				break;
			}
			$bytes /= 1024;
		}

		if ($result == 1) {
			$size = KInflector::singularize($size);
		}

		return sprintf($format, $result, JText::_($size));
	}
}