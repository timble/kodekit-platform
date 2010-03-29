<?php
/**
 * @version		$Id$
 * @package		Profiles
 * @copyright	Copyright (C) 2009 Nooku. All rights reserved.
 * @license 	GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.nooku.org
 */


/**
 * Because Offices and departments work basically in the same way, we have an
 * abstract model to represent them both
 */
abstract class ComProfilesModelGroups extends KModelTable
{
    /**
	 * All the items
	 *
	 * @var array
	 */
	protected $_all;
	
	public function __construct(KConfig $config)
	{
		$config->table_behaviors = array('hittable', 'lockable', 'creatable', 'modifiable');
		
		parent::__construct($config);
	}
	
	public function getAll()
	{
        // Get the data if it doesn't already exist
        if (!isset($this->_all))
        {
        	$table = KFactory::get($this->getTable());
        	$query = $table->getDatabase()->getQuery()->select(array('*'));
        	
        	$this->_all = $table->select($query);	
        }

        return $this->_all;
	}
	
	protected function _buildQueryWhere(KDatabaseQuery $query)
	{
		$state = $this->_state;
		
		if($state->search) {
			$query->where('tbl.title', 'LIKE',  '%'.$state->search.'%');
		}

		if($state->enabled) {
			$query->where('tbl.enabled','=', $state->enabled);
		}
		
		parent::_buildQueryWhere($query);
	}
}