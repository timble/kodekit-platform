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

class ComFilesModelFolders extends ComFilesModelNodes
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
		    $state = $this->_state;
            
			$this->_item = $this->getRow(array(
				'data' => array(
            		'container' => $this->_state->container,
                    'basepath' => $this->_getPath(),
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
			
			$folders = ComFilesIteratorDirectory::getFolders(array(
				'path' => $this->_getPath(),
				'recurse' => !!$state->tree,
				'filter' => array($this, 'iteratorFilter'),
				'map' => array($this, 'iteratorMap')
			));
			$this->_total = count($folders);

    	    if ($state->path) 
    	    {
    	        $f = array();
    	        foreach ((array) $state->path as $folder) {
                    if (in_array($folder, $folders)) {
                        $f[] = $folder;
                    }
                }
                $folders = $f;
    	    }

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
					'basepath' => $state->container->path,
					'path' => $folder,
					'hierarchy' => $hier
				);
			}

			$this->_list = $this->getRowset()->addData($results);
		}

		return parent::getList();
	}

	public function iteratorMap($folder)
	{
		$path = str_replace('\\', '/', $folder->getPathname());
		$path = str_replace($this->_state->container->path.'/', '', $path);

		return $path;
	}

	public function iteratorFilter($folder)
	{
		if ($this->_state->search && stripos($folder->getBasename(), $this->_state->search) === false) {
			return false;
		}
	}
}
