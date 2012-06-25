<?php
/**
 * @version     $Id$
 * @package     Nooku_Components
 * @subpackage  Files
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Custom directory iterator with additional filters and callbacks.
 *
 * @author      Ercan Ozkaya <http://nooku.assembla.com/profile/ercanozkaya>
 * @package     Nooku_Components
 * @subpackage  Files
 */
class ComFilesIteratorDirectory extends DirectoryIterator
{
	/**
	 *
	 * Method to get files in a folder
	 *
	 * @param array $config
	 */
	public static function getFiles($config = array())
	{
		$config['type'] = 'files';
		return self::getNodes($config);
	}

	/**
	 *
	 * Method to get child folders of a folder
	 *
	 * @param array $config
	 */
	public static function getFolders($config = array())
	{
		$config['type'] = 'folders';
		return self::getNodes($config);
	}

	/**
	 *
	 * Method to read child nodes of a folder
	 *
	 * @param array $config
	 */
	public static function getNodes($config = array())
	{
		$config = new KConfig($config);
		$config->append(array(
			'path' 		=> null, // path to the directory
			'type' 		=> null, // folders or files, null for both
			'recurse' 	=> false, // boolean or integer to specify the depth
			'fullpath' 	=> false, // true to return full paths, false to return basename only
			'filter' 	=> null, // either an array of file extensions, a regular expression or a callback function
			'map' 		=> null, // a callback to return values from items in the iterator
			'exclude' 	=> array('.svn', '.git', 'CVS') // an array of values to exclude from results
		));

		$exclude = KConfig::unbox($config->exclude);
		$filter = KConfig::unbox($config->filter);
		$map = KConfig::unbox($config->map);
		$recurse = $config->recurse;

		$results = array();
		foreach (new self($config->path) as $file)
		{
			if ($file->isDot() || in_array($file->getFilename(), $exclude)) {
				continue;
			}

			if ($file->isDir() && !$file->isDot() && $recurse) 
			{
				$clone = clone $config;
				$clone->path = $file->getPathname();
				$clone->recurse = is_int($config->recurse) ? $config->recurse - 1 : $config->recurse;
				$clone->return_raw = true;
				$child_results = self::getNodes($clone);
			}

			if ($config->type) 
			{
				$method = 'is'.ucfirst($config->type === 'files' ? 'file' : 'dir');
				if (!$file->$method()) {
					continue;
				}
			}

			if ($filter) 
			{
				if (is_callable($filter)) {
					$ignore = call_user_func($filter, rawurldecode($file->getPathname())) === false;
				} else if (is_array($filter)) {
					$ignore = !in_array(strtolower($file->getExtension()), $filter);
				} else if (is_string($filter)) {
					$ignore = !preg_match("/$filter/", $file->getFilename());
				}
				if ($ignore) {
					continue;
				}
			}

			if (is_callable($map)) {
				$result = call_user_func($map, rawurldecode($file->getPathname()));
			} else {
				$result = $config->fullpath ? $file->getPathname() : $file->getFilename();
			}
			$results[] = array('path' => $result, 'modified' => $file->getMTime());

			if (!empty($child_results)) {
				$results = array_merge($results, $child_results);
			}
		}
		
		if ($config->sort === 'modified_on') {
			uasort($results, array('self', '_sortByDate'));
		}
		
		if ($config->return_raw === true) {
			return $results;
		}

		$return = array();
		foreach ($results as $result) {
			$return[] = $result['path'];
		}
		
		return $return;
	}

	public function getExtension()
	{
		$filename  = $this->getFilename();
		$extension = pathinfo($filename, PATHINFO_EXTENSION);
		return strtolower($extension);
	}
	
	public static function _sortByDate($file1, $file2)
	{
		return strcmp($file1['modified'], $file2['modified']);
	}
}


