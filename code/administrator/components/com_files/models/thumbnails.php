<?php
/**
 * @version     $Id$
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Files
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Thumbnails Model Class
 *
 * @author      Ercan Ozkaya <http://nooku.assembla.com/profile/ercanozkaya>
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Files
 */
class ComFilesModelThumbnails extends ComDefaultModelDefault
{
	public function __construct(KConfig $config)
	{
		parent::__construct($config);

		$this->_state
			->insert('container', 'com://admin/files.filter.container', null)
			->insert('folder', 'com://admin/files.filter.path')
			->insert('filename', 'com://admin/files.filter.path', null, true, array('container'))
			->insert('files', 'com://admin/files.filter.path', null)
			->insert('source', 'raw', null, true)
			;
		
	}
	
	protected function _initialize(KConfig $config)
	{
		$config->append(array(
			'state' => new ComFilesConfigState()
		));
		
		parent::_initialize($config);
	}

	public function getItem()
	{
		$item = parent::getItem();

		if ($item) {
			$item->source = $this->_state->source;
		}

		return $item;
	}

	protected function _buildQueryColumns(KDatabaseQuery $query)
    {
    	parent::_buildQueryColumns($query);
    	
    	if ($this->_state->source instanceof KDatabaseRowInterface || $this->_state->container) {
    		$query->select('c.slug AS container');
    	}
    }
	
	protected function _buildQueryJoins(KDatabaseQuery $query)
    {
    	parent::_buildQueryJoins($query);
    	
    	if ($this->_state->source instanceof KDatabaseRowInterface || $this->_state->container) {
    		$query->join('LEFT', 'files_containers AS c', 'c.files_container_id = tbl.files_container_id');
    	}
    }

	protected function _buildQueryWhere(KDatabaseQuery $query)
    {
        $state = $this->_state;
		if ($state->source instanceof KDatabaseRowInterface) {
			$source = $state->source;

			$query->where('tbl.files_container_id', '=', $source->container->id)
				->where('tbl.filename', '=', $source->name);

			if ($source->folder) {
				$query->where('tbl.folder', '=', $source->folder);
			}
		}
		elseif (!empty($state->files)) {
			$query->where('tbl.filename', 'IN', $state->files);
		}
		else {
		    if ($state->container) {
		        $query->where('tbl.files_container_id', '=', $state->container->id);
		    }
		    
		    if ($state->folder !== false) {
		    	$query->where('tbl.folder', '=', ltrim($state->folder, '/'));	
		    }

		    if ($state->filename) {
		        $query->where('tbl.filename', '=', $state->filename);
		    }
		}

	}
}
