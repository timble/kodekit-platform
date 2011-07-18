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
 * Folders Model Class
 *
 * @author      Ercan Ozkaya <http://nooku.assembla.com/profile/ercanozkaya>
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Files   
 */

jimport('joomla.filesystem.folder');

class ComFilesModelFolders extends ComFilesModelDefault
{
	public function __construct(KConfig $config)
	{
		parent::__construct($config);

		$this->_state->insert('tree', 'boolean', false);
	}

	public function getItem()
	{
		if (!isset($this->_item)) 
		{
			$this->_item	= KFactory::tmp('admin::com.files.database.row.folder', array(
				'data' => array(
					'basepath' => $this->_state->basepath,
					'path' => $this->_state->path
				)));
		}

		return parent::getItem();
	}

	public function getList()
	{
		if (!isset($this->_list)) 
		{
			$state = $this->_state;
			if (!$state->basepath) {
				throw new KModelException('Basepath is not a valid folder');
			}

			$basepath = $state->basepath;
			$basepath = rtrim(str_replace('\\', '/', $basepath), '\\');
			$path = $state->basepath;

			if (!empty($state->folder) && $state->folder != '/') {
				$path .= '/'.ltrim($state->folder, '/');
			}

			if (!JFolder::exists($path)) {
				throw new KModelException('Basepath is not a valid folder');
			}

			$name = $state->path ? $state->path : null;
			if (is_string($name)) {
				$folders[] = $name;
			}
			else if (is_array($name)) 
			{
				$folders = array();
				foreach ($name as $n) {
					$folders[] = $n;
				}
			}
			else 
			{
				$folders = JFolder::folders($path, '.', $state->tree ? true : false, true, array('.svn', '.git', 'CVS'));

				foreach ($folders as &$folder) {
					$folder = str_replace('\\', '/', $folder);
					$folder = str_replace($basepath.'/', '', $folder);
				}
				unset($folder);
			}

			$search = $state->search;

			if ($search) {
				foreach ($folders as $i => $folder) {
					if (stripos($folder, $search) === false) {
						unset($folders[$i]);
					}
				}
			}

			$this->_total = count($folders);

			$folders = array_slice($folders, $state->offset, $state->limit ? $state->limit : $this->_total);

			if (strtolower($this->_state->direction) == 'desc') {
				$folders = array_reverse($folders);
			}

			$rowset = KFactory::tmp('admin::com.files.database.rowset.folders');

			foreach ($folders as $folder) 
			{
				$row = KFactory::tmp('admin::com.files.database.row.folder', array(
					'data' => array(
						'basepath' => $basepath,
						'path' => $folder
					)
				));

				if ($state->tree && count(explode('/', $folder)) != 1) 
				{
					$base = dirname($folder);

					$parts = explode('/', $base);
					$parent = $rowset->find($parts[0]);
					for ($i = 2; $i <= count($parts); $i++) {
						$needle = implode('/', array_slice($parts, 0, $i));
						$parent = $parent->children->find($needle);
					}

					$parent->children->insert($row);
				}
				else $rowset->insert($row);
			}

			$this->_list = $rowset;
		}

		return parent::getList();
	}
}