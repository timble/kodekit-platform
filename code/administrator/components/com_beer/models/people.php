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
		 	->insert('fletter'  			, 'word')
		 	->insert('lletter'   			, 'word')
		 	->insert('enabled'   			, 'boolean', false);
	}
	
	public function getLettersFirstname()
	{
		$query = $this->_db->getQuery()
			->select('letter_firstname AS fletter')
			->distinct()
			->from('beer_viewpeople AS tbl')
			->order('tbl.letter_firstname');
					
		return $this->getView()->fetchRowset($query);
	}
	
	public function getLettersLastname()
	{
		$query = $this->_db->getQuery()
			->select('letter_lastname AS lletter')
			->distinct()
			->from('beer_viewpeople AS tbl')
			->order('tbl.letter_lastname');
						        
		return $this->getView()->fetchRowset($query);
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
		
		if ( $state->lletter) {
			$query->where('tbl.lastname', 'Like',  $state->lletter.'%');	
		}
		if ( $state->fletter) {
			$query->where('tbl.firstname', 'Like',  $state->fletter.'%');	
		}
	}
}