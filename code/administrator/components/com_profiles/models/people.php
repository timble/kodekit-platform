<?php
/**
 * @version		$Id$
 * @package		Profiles
 * @copyright	Copyright (C) 2009 Nooku. All rights reserved.
 * @license 	GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.nooku.org
 */

class ComProfilesModelPeople extends ComDefaultModelView
{
	public function __construct(array $options = array())
	{
		$options['table_behaviors'] = array('hittable', 'lockable', 'creatable', 'modifiable');
		
		parent::__construct($options);
		
		// Set the state
		$this->_state
		 	->insert('profiles_department_id'   , 'int')
		 	->insert('profiles_office_id'      	, 'int')
		 	->insert('letter_name'  			, 'word')
		 	->insert('enabled'   				, 'boolean', false);
	}
	
	public function getLetters()
	{
		$query = $this->_db->getQuery()
			->select('letter_name')
			->distinct()
			->from('profiles_view_people AS tbl')
			->order('tbl.letter_name');
		
		$result = (array) $this->_db->fetchResultList($query);
		return $result; 
	}
	
	protected function _buildQueryWhere(KDatabaseQuery $query)
	{
		$state = $this->_state;
		
		if($state->enabled) {
			$query->where('tbl.enabled','=', $state->enabled);
		}

		if ( $state->profiles_department_id) {
			$query->where('tbl.profiles_department_id','=', $state->profiles_department_id);
		}

		if ( $state->profiles_office_id) {
			$query->where('tbl.profiles_office_id','=', $state->profiles_office_id);
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