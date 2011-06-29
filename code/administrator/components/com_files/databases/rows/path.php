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
 * Path Database Row Class
 *
 * @author      Ercan Ozkaya <http://nooku.assembla.com/profile/ercanozkaya>
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Files
 */

class ComFilesDatabaseRowPath extends KDatabaseRowDefault
{
	protected function _initialize(KConfig $config)
	{
		$config->append(array(
			'identity_column' => 'identifier'
		));

		parent::_initialize($config);
	}

	public function __get($column)
	{
		if ($column == 'path' && !empty($this->_data['path']))
		{
			$result = $this->_data['path'];
			// Prepend with site root if it is a relative path
			if (!preg_match('#^(?:[a-z]\:|~*/)#i', $result)) {
				$result = JPATH_FILES.'/'.$result;
			}

			$result = rtrim(str_replace('\\', '/', $result), '\\');
			return $result;
		}
		else if ($column == 'relative_path') {
			return $this->getRelativePath();
		}
		else if ($column == 'path_value') {
			return $this->_data['path'];
		}

		return parent::__get($column);
	}

	public function __toString()
	{
		return (string) $this->path;
	}

	public function getRelativePath()
	{
		$path = $this->path;
		$root = str_replace('\\', '/', JPATH_ROOT);
		return str_replace($root.'/', '', $path);
	}
}