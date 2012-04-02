<?php
/**
 * @version    	$Id$
 * @category	Nooku
 * @package    	Nooku_Server
 * @subpackage 	Extensions
 * @copyright  	Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license    	GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link       	http://www.nooku.org
 */

/**
 * Modules Model Class
 *
 * @author		Stian Didriksen <http://nooku.assembla.com/profile/stiandidriksen>
 * @category	Nooku
 * @package		Nooku_Server
 * @subpackage	Extensions  
 */

class ComExtensionsModelModules extends ComDefaultModelDefault
{
	public function __construct(KConfig $config)
	{
		parent::__construct($config);
	
		$this->getState()
		 	->insert('application', 'cmd')
		 	->insert('sort'  	  , 'cmd', array('position', 'ordering'))
		 	->insert('enabled'	  , 'boolean')
		 	->insert('position'   , 'cmd')
		 	->insert('type' 	  , 'cmd')
		 	->insert('installed'  , 'boolean', false)
		 	->insert('hidden'     , 'boolean');
	}

	protected function _buildQueryJoin(KDatabaseQuerySelect $query)
	{
		$query
		    ->join(array('user' => 'users'), 'user.id = tbl.checked_out')
		    ->join(array('group' => 'groups'), 'group.id = tbl.access')
		    ->join(array('module_menu' => 'modules_menu'), 'module_menu.moduleid = tbl.id');

		parent::_buildQueryJoin($query);
	}

	protected function _buildQueryWhere(KDatabaseQuerySelect $query)
	{
		$state = $this->getState();

		if($state->search) {
		    $query->where('tbl.title LIKE :search')->bind(array('search' => '%'.$state->search.'%'));
		}

		if($state->position) {
		    $query->where('tbl.position = :position')->bind(array('position' => $state->position));
		}
		
		if($state->type) {
		    $query->where('tbl.module = :type')->bind(array('type' => $state->type));
		}

		if(is_bool($state->enabled)) {
		    $query->where('tbl.published = :enabled')->bind(array('enabled' => (int) $state->enabled));
		}
		
	    if(is_bool($state->hidden)) {
	        $query->where('tbl.iscore = :hidden')->bind(array('hidden' => (int) $state->hidden));
		}
		
		if($state->application && is_scalar($state->application)) {
		    $client	= JApplicationHelper::getClientInfo($state->application, true);
		    $query->where('tbl.client_id = :client')->bind(array('client' => $client->id));
	    }

		parent::_buildQueryWhere($query);
	}

	/**
	 * Method to get a item object which represents a table row
	 *
	 * If the model state is unique a row is fetched from the database based on the state.
	 * If not, an empty row is be returned instead.
	 *
	 * This method is customized in order to set the default module type on new rows.
	 *
	 * @return KDatabaseRow
	 */
	public function getItem()
	{
		if (!isset($this->_item))
		{
			$this->_item = parent::getItem();

			if($this->_item->isNew() && $this->getState()->type) 
			{
			    $client	                = JApplicationHelper::getClientInfo($this->getState()->application, true);
			    $this->_item->client_id = $client->id;
				$this->_item->type      = $this->getState()->type;
			}
		}

		return $this->_item;
	}

    /**
     * Get a list of items
     * 
     * If the installed state is TRUE this function will return a list of the installed
     * modules.
     *
     * @return KDatabaseRowsetInterface
     */
    public function getList()
    { 
        if(!isset($this->_list))
        {
            $state = $this->getState();
           
            if($state->installed)
            {
                $modules = array();
               	$ids = parent::getList()->type; 

                foreach((array) KConfig::unbox($state->application) as $application)
                {
                    $client	= JApplicationHelper::getClientInfo($application, true);
            	    if(!empty($client)) 
			        {        
            	        $path = $client->path.'/modules';
            	    
			            foreach(new DirectoryIterator($path) as $folder)
                        {
                            if($folder->isDir())
                            {
                                if(file_exists($folder->getRealPath().'/'.$folder->getFilename().'.xml')) 
                                {
                                    $modules[] = array(
                                    	'id'          => $folder->getFilename(),
                       					'type'        => $folder->getFilename(),
                        				'application' => $client->name,
                                    	'title'		  => null
	                                    );
                                }
                            }
                        }    
			        }
                }
                
                //Set the total
			    $this->_total = count($modules);

                //Apply limit and offset
                if($this->getState()->limit) {
                    $modules = array_slice($modules, $state->offset, $state->limit ? $state->limit : $this->_total);
                }
                
			    //Apply direction
			    if(strtolower($state->direction) == 'desc') {
	                $modules = array_reverse($modules);
			    }
                
                $this->_list = $this->getTable()->getRowset()->addData($modules);
                       
            } else $this->_list = parent::getList();
        }

        return $this->_list;
    }
}