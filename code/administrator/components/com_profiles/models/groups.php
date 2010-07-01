<?php
/**
 * @version		$Id$
 * @package		Profiles
 * @copyright	Copyright (C) 2009 - 2010 Nooku. All rights reserved.
 * @license 	GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.nooku.org
 */

/**
 * Because Offices and departments work basically in the same way, we have an
 * abstract model to represent them both
 */
abstract class ComProfilesModelGroups extends KModelTable
{
	public function __construct(KConfig $config)
	{
		parent::__construct($config);
		
		$this->_state
		 	->insert('enabled', 'int'); 
	}
	
	protected function _buildQueryWhere(KDatabaseQuery $query)
	{
		$state = $this->_state;
		
		if($state->search) {
			$query->where('tbl.title', 'LIKE',  '%'.$state->search.'%');
		}

		if( is_numeric($state->enabled)) {
			$query->where('tbl.enabled','=', $state->enabled);
		}
		
		parent::_buildQueryWhere($query);
	}
}