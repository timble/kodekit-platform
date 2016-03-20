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
 * Iterator Adapter
 *
 * @author  Ercan Ozkaya <http://github.com/ercanozkaya>
 * @package Kodekit\Component\Files
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