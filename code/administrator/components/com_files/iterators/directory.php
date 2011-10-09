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
 * Custom directory iterator with additional filters and callbacks.
 *
 * @author      Ercan Ozkaya <http://nooku.assembla.com/profile/ercanozkaya>
 * @category	Nooku
 * @package     Nooku_Server
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
			'path' => null, // path to the directory
			'type' => null, // folders or files, null for both
			'recurse' => false, // boolean or integer to specify the depth
			'fullpath' => false, // true to return full paths, false to return basename only
			'filter' => null, // either an array of file extensions, a regular expression or a callback function
			'map' => null, // a callback to return values from items in the iterator
			'exclude' => array('.svn', '.git', 'CVS') // an array of values to exclude from results
		));

		$exclude = KConfig::unbox($config->exclude);
		$filter = KConfig::unbox($config->filter);
		$map = KConfig::unbox($config->map);
		$recurse = $config->recurse;

		$results = array();
		foreach (new self($config->path) as $file) {
			if ($file->isDot()
				|| in_array($file->getFilename(), $exclude)
			) continue;

			if ($file->isDir() && !$file->isDot() && $recurse) {
				$clone = clone $config;
				$clone->path = $file->getPathname();
				$clone->recurse = is_int($config->recurse) ? $config->recurse - 1 : $config->recurse;
				$child_results = self::getNodes($clone);
			}

			if ($config->type) {
				$method = 'is'.ucfirst($config->type === 'files' ? 'file' : 'dir');
				if (!$file->$method()) {
					continue;
				}
			}

			if ($filter) {
				if (is_callable($filter)) {
					$ignore = call_user_func($filter, $file) === false;
				} else if (is_array($filter)) {
					$ignore = !in_array($file->getExtension(), $filter);
				} else if (is_string($filter)) {
					$ignore = !preg_match("/$filter/", $file->getFilename());
				}
				if ($ignore) {
					continue;
				}
			}

			if (is_callable($map)) {
				$result = call_user_func($map, $file);
			}
			else {
				$result = $config->fullpath ? $file->getPathname() : $file->getFilename();
			}

			$results[] = $result;

			if (!empty($child_results)) {
				$results = array_merge($results, $child_results);
			}
		}

		return $results;
	}

	public function getExtension()
	{
		$filename = $this->getFilename();
		$extension = pathinfo($filename, PATHINFO_EXTENSION);
		return strtolower($extension);
	}
}
