<?php
/**
 * @package     Nooku_Components
 * @subpackage  Files
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

use Nooku\Framework;

/**
 * Thumbnails Model Class
 *
 * @author      Ercan Ozkaya <http://nooku.assembla.com/profile/ercanozkaya>
 * @package     Nooku_Components
 * @subpackage  Files
 */
class ComFilesModelThumbnails extends ComDefaultModelDefault
{
	public function __construct(Framework\Config $config)
	{
		parent::__construct($config);

		$this->getState()
			->insert('container' , 'com://admin/files.filter.container', null)
			->insert('folder'    , 'com://admin/files.filter.path')
			->insert('filename'  , 'com://admin/files.filter.path', null, true, array('container'))
			->insert('files'     , 'com://admin/files.filter.path', null)
			->insert('source'    , 'raw', null, true)
		    ->insert('types'     , 'cmd', '')
		    ->insert('config'    , 'json', '');
	}
	
	protected function _initialize(Framework\Config $config)
	{
		$config->append(array(
			'state' => new ComFilesModelState()
		));
		
		parent::_initialize($config);
	}

	public function getRow()
	{
		$item = parent::getRow();

		if ($item) {
			$item->source = $this->getState()->source;
		}

		return $item;
	}

	protected function _buildQueryColumns(Framework\DatabaseQuerySelect $query)
    {
    	parent::_buildQueryColumns($query);
    	$state = $this->getState();
    	
    	if ($state->source instanceof Framework\DatabaseRowInterface || $state->container) {
    		$query->columns(array('container' => 'containers.slug'));
    	}
    }
	
	protected function _buildQueryJoins(Framework\DatabaseQuerySelect $query)
    {
    	parent::_buildQueryJoins($query);
    	$state = $this->getState();
    	
    	if ($state->source instanceof Framework\DatabaseRowInterface || $state->container) {
    		$query->join(array('containers' => 'files_containers'), 'containers.files_container_id = tbl.files_container_id');
    	}
    }

	protected function _buildQueryWhere(Framework\DatabaseQuerySelect $query)
    {
        $state = $this->getState();
        
		if ($state->source instanceof Framework\DatabaseRowInterface) {
			$source = $state->source;

			$query->where('tbl.files_container_id = :container_id')
				->where('tbl.filename = :filename')
				->bind(array('container_id' => $source->container->id, 'filename' => $source->name));

			if ($source->folder) {
				$query->where('tbl.folder = :folder')->bind(array('folder' => $source->folder));
			}
		}
		else 
		{
		    if ($state->container) {
		        $query->where('tbl.files_container_id = :container_id')->bind(array('container_id' => $state->container->id));
		    }
		    
		    if ($state->folder !== false) {
		    	$query->where('tbl.folder = :folder')->bind(array('folder' => ltrim($state->folder, '/')));
		    }
		    
		    // Need this for BC
		    if (!empty($state->files)) {
		        $query->where('tbl.filename IN :files')->bind(array('files' => $state->files));
		    } elseif ($state->filename) {
		        $query->where('tbl.filename IN :filename')->bind(array('filename' => (array) $state->filename));
		    }
		}
	}
	
	protected function _buildQueryOrder(Framework\DatabaseQuerySelect $query)
	{
		$sort       = $this->_state->sort;
		$direction  = strtoupper($this->_state->direction);
		
		if($sort) 
		{
			$column = $this->getTable()->mapColumns($sort);
			if(array_key_exists($column, $this->getTable()->getColumns())) {
				$query->order($column, $direction);
			}
		}	
	}
}
