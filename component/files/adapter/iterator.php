<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Component\Files;

use Nooku\Library;

/**
 * Iterator Adapter
 *
 * @author  Ercan Ozkaya <http://github.com/ercanozkaya>
 * @package Nooku\Component\Files
 */
class AdapterIterator extends Library\Object
{
	public function getFiles(array $config = array())
	{
		$config['type'] = 'files';
		return self::getNodes($config);
	}

	public function getFolders(array $config = array())
	{
		$config['type'] = 'folders';
		return self::getNodes($config);
	}

	public function getNodes(array $config = array())
	{
		$config['path'] = $this->getObject('com:files.adapter.folder',
					array('path' => $config['path']))->getRealPath();

		try {
			$results = IteratorDirectory::getNodes($config);
		}
		catch (Exception $e) {
			return false;
		}

		foreach ($results as &$result) {
			$result = rawurldecode($result);
		}

		return $results;
	}
}