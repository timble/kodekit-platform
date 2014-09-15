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
 * Folder Adapter
 *
 * @author  Ercan Ozkaya <http://github.com/ercanozkaya>
 * @package Nooku\Component\Files
 */
class AdapterFolder extends AdapterAbstract
{
	public function move($target)
	{
		$result = false;
		$encoded = $this->encodePath($target);
		$dir = dirname($encoded);

		if (!is_dir($encoded)) {
			$result = mkdir($encoded, 0755, true);
		}

		if (is_dir($encoded))
		{
			$result = true; // needed for empty directories
			$iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($this->_encoded), \RecursiveIteratorIterator::SELF_FIRST);
			foreach ($iterator as $f)
			{
				if ($f->isDir())
				{
					$path = $encoded.'/'.$iterator->getSubPathName();
					if (!is_dir($path)) {
						$result = mkdir($path);
					}
				}
				else $result = copy($f, $encoded.'/'.$iterator->getSubPathName());

				if ($result === false) {
					break;
				}
			}
		}

		if ($result && $this->delete()) {
			$this->setPath($target);
		} else {
			$result = false;
		}

		return $result;
	}

	public function copy($target)
	{
		$result = false;
		$encoded = $this->encodePath($target);
		$dir = dirname($encoded);

		if (!is_dir($encoded)) {
			$result = mkdir($encoded, 0755, true);
		}

		if (is_dir($encoded))
		{
			$result = true; // needed for empty directories
			$iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($this->_encoded), \RecursiveIteratorIterator::SELF_FIRST);
			foreach ($iterator as $f)
			{
				if ($f->isDir()) {
					$path = $encoded.'/'.$iterator->getSubPathName();
					if (!is_dir($path)) {
						$result = mkdir($path);
					}
				} else {
					$result = copy($f, $encoded.'/'.$iterator->getSubPathName());
				}

				if ($result === false) {
					break;
				}
			}
		}

		if ($result) {
			$this->setPath($target);
		}

		return $result;
	}

	public function delete()
	{
		if (!file_exists($this->_encoded)) {
			return true;
		}

		$iter = new \RecursiveDirectoryIterator($this->_encoded, \FilesystemIterator::SKIP_DOTS);
		foreach (new \RecursiveIteratorIterator($iter, \RecursiveIteratorIterator::CHILD_FIRST) as $f)
		{
			if ($f->isDir()) {
				rmdir($f->getPathname());
			} else {
				unlink($f->getPathname());
			}
		}

		return rmdir($this->_encoded);
	}

	public function create()
	{
		$result = true;

		if (!is_dir($this->_encoded)) {
			$result = mkdir($this->_encoded, 0755, true);
		}

		return $result;
	}

	public function exists()
	{
		return is_dir($this->_encoded);
	}
}
