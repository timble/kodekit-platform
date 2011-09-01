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
			$this->_item = KFactory::get('com://admin/files.database.row.folder', array(
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

			$state->basepath = rtrim(str_replace('\\', '/', $state->basepath), '\\');
			$path = $state->basepath;

			if (!empty($state->folder) && $state->folder != '/') {
				$path .= '/'.ltrim($state->folder, '/');
			}

			if (!$state->basepath || !is_dir($path)) {
				throw new KModelException('Basepath is not a valid folder');
			}

			if (!empty($state->path)) {
				$folders = array();
				foreach ((array) $state->path as $path) {
					$folders[] = $path;
				}
			} else {
				$folders = ComFilesIteratorDirectory::getFolders(array(
					'path' => $path,
					'recurse' => !!$state->tree,
					'filter' => array($this, 'iteratorFilter'),
					'map' => array($this, 'iteratorMap')
				));
			}

			$this->_total = count($folders);
			$folders = array_slice($folders, $state->offset, $state->limit ? $state->limit : $this->_total);

			if (strtolower($this->_state->direction) == 'desc') {
				$folders = array_reverse($folders);
			}

			$results = array();
			foreach ($folders as $folder)
			{
				$hier = array();
				if ($state->tree) {
					$hier = explode('/', dirname($folder));
					if (count($hier) === 1 && $hier[0] === '.') {
						$hier = array();
					}
				}

				$results[] = array(
					'basepath' => $state->basepath,
					'path' => $folder,
					'hierarchy' => $hier
				);
			}

			$rowset = KFactory::get('com://admin/files.database.rowset.folders');
			$rowset->addData($results);

			$this->_list = $rowset;
		}

		return parent::getList();
	}

	public function iteratorMap($folder)
	{
		$path = str_replace('\\', '/', $folder->getPathname());
		$path = str_replace($this->_state->basepath.'/', '', $path);

		return $path;
	}

	public function iteratorFilter($folder)
	{
		if ($this->_state->search && stripos($folder->getBasename(), $this->_state->search) === false) {
			return false;
		}
	}

	public function getColumn($column)
	{
		return $this->getList();
	}
}
