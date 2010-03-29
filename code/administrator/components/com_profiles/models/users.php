<?php
/**
 * @version		$Id$
 * @package		Profiles
 * @copyright	Copyright (C) 2009 Nooku. All rights reserved.
 * @license 	GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.nooku.org
 */

class ComProfilesModelUsers extends KModelTable
{
	public function __construct(KConfig $config)
	{
		parent::__construct($config);
		
		// Set the state
		$this->_state->insert('gid', 'int');
	}

	public function getGroups()
	{
		$database = KFactory::get($this->getTable())->getDatabase();
		
		$query = $database->getQuery()->select(array('gid', 'usertype'))
			->from('profiles_users AS tbl')
			->group('gid')
			->order('gid');
				
		$result = $database->fetchObjectList($query);
		return $result; 
	}
	
	protected function _buildQueryWhere(KDatabaseQuery $query)
	{
		if($this->_state->search) {
			$query->where('tbl.name', 'LIKE',  '%'.$this->_state->search.'%');
		}
		
		if ($this->_state->gid) {
			$query->where('tbl.gid','=', $this->_state->gid);
		}
		
		parent::_buildQueryWhere($query);
	}
}