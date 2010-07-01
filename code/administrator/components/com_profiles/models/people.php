<?php
/**
 * @version		$Id$
 * @package		Profiles
 * @copyright	Copyright (C) 2009 - 2010 Nooku. All rights reserved.
 * @license 	GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.nooku.org
 */

class ComProfilesModelPeople extends KModelTable
{
	public function __construct(KConfig $config)
	{
		parent::__construct($config);
		
		$this->_state
		 	->insert('department'   , 'int')
		 	->insert('office'      	, 'int')
		 	->insert('letter_name'  , 'word')
		 	->insert('enabled'   	, 'int');
	}
	
	public function getLetters()
	{
		$database = KFactory::get($this->getTable())->getDatabase();
		
		$query = $database->getQuery()
			->select('letter_name')
			->distinct()
			->from('profiles_view_people AS tbl')
			->order('tbl.letter_name');
		
		$result = (array) $database->fetchFieldList($query);
		return $result; 
	}
	
	protected function _buildQueryWhere(KDatabaseQuery $query)
	{
		$state = $this->_state;
		
		if( is_numeric($state->enabled)) {
			$query->where('tbl.enabled','=', $state->enabled);
		}

		if ( $state->department) {
			$query->where('tbl.profiles_department_id','=', $state->department);
		}

		if ( $state->office) {
			$query->where('tbl.profiles_office_id','=', $state->office);
		}

		if ( $state->search) 
		{
			$search = '%'.$state->search.'%';
			$query->where('tbl.name', 'LIKE',  $search);
		}
		
		if ( $state->letter_name) {
			$query->where('tbl.lastname', 'Like',  $state->letter_name.'%');	
		}
		
		parent::_buildQueryWhere($query);
	}
}