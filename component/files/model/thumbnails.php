<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

namespace Nooku\Component\Files;

use Nooku\Library;

/**
 * Thumbnails Model
 *
 * @author  Ercan Ozkaya <http://nooku.assembla.com/profile/ercanozkaya>
 * @package Nooku\Component\Files
 */
class ModelThumbnails extends Library\ModelTable
{
    protected $_container;

    public function __construct(Library\ObjectConfig $config)
	{
		parent::__construct($config);

		$this->getState()
			->insert('container' , 'com:files.filter.container', null)
			->insert('folder'    , 'com:files.filter.path')
			->insert('filename'  , 'com:files.filter.path', null, true, array('container'))
			->insert('files'     , 'com:files.filter.path', null)
			->insert('source'    , 'raw', null, true)
		    ->insert('types'     , 'cmd', '')
		    ->insert('config'    , 'json', '');
	}

	protected function _initialize(Library\ObjectConfig $config)
	{
		$config->append(array(
            'state' => 'com:files.model.state'
		));

		parent::_initialize($config);
	}

    public function getContainer()
    {
        if(!isset($this->_container))
        {
            //Set the container
            $container = $this->getObject('com:files.model.containers')->slug($this->getState()->container)->getRow();

            if (!is_object($container) || $container->isNew()) {
                throw new \UnexpectedValueException('Invalid container');
            }

            $this->_container = $container;
        }

        return $this->_container;
    }

	protected function _buildQueryColumns(Library\DatabaseQuerySelect $query)
    {
    	parent::_buildQueryColumns($query);

    	$state = $this->getState();
    	
    	if ($state->container) {
    		$query->columns(array('container' => 'containers.slug'));
    	}
    }
	
	protected function _buildQueryJoins(Library\DatabaseQuerySelect $query)
    {
    	parent::_buildQueryJoins($query);

    	$state = $this->getState();
    	
    	if ($state->container) {
    		$query->join(array('containers' => 'files_containers'), 'containers.files_container_id = tbl.files_container_id');
    	}
    }

	protected function _buildQueryWhere(Library\DatabaseQuerySelect $query)
    {
        $state = $this->getState();

        if ($state->container) {
            $query->where('tbl.files_container_id = :container_id')->bind(array('container_id' => $this->getContainer()->id));
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
	
	protected function _buildQueryOrder(Library\DatabaseQuerySelect $query)
	{
		$sort      = $this->getState()->sort;
		$direction = strtoupper($this->getState()->direction);
		
		if($sort) 
		{
			$column = $this->getTable()->mapColumns($sort);
			if(array_key_exists($column, $this->getTable()->getColumns())) {
				$query->order($column, $direction);
			}
		}	
	}
}
