<?php
/**
 * Business Enterprise Employee Repository (B.E.E.R)
 * 
 * @version		$Id$
 * @package		Beer
 * @copyright	Copyright (C) 2009 Nooku. All rights reserved.
 * @license 	GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.nooku.org
 */

koowa::import('admin::com.beer.models.view');

class BeerModelPeople extends BeerModelView
{
	public function __construct(array $options = array())
	{
		parent::__construct($options);
		
		// Set the state
		$this->_state
		 	->insert('beer_department_id'   , 'int')
		 	->insert('beer_office_id'      	, 'int')
		 	->insert('letter_name'  		, 'word')
		 	->insert('enabled'   			, 'boolean', false);
	}
	
	public function getLetters()
	{
		$query = $this->_db->getQuery()
			->select('letter_name')
			->distinct()
			->from('beer_viewpeople AS tbl')
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

		if ( $state->beer_department_id) {
			$query->where('tbl.beer_department_id','=', $state->beer_department_id);
		}

		if ( $state->beer_office_id) {
			$query->where('tbl.beer_office_id','=', $state->beer_office_id);
		}

		if ( $state->search) 
		{
			$search = '%'.$state->search.'%';

			$query->where('tbl.firstname', 'LIKE',  $search)
				  ->where('tbl.lastname', 'LIKE', $search, 'or')
				  ->where('tbl.bio', 'LIKE', $search, 'or');
		}
		
		if ( $state->letter_name) {
			$query->where('tbl.lastname', 'Like',  $state->letter_name.'%');	
		}
	}
}