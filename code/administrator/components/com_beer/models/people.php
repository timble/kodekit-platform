<?php
/**
 * Business Enterprise Employee Repository (B.E.E.R)
 * @version		$Id$
 * @package		Beer
 * @copyright	Copyright (C) 2009 Nooku. All rights reserved.
 * @license 	GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.nooku.org
 */

class BeerModelPeople extends KModelTable
{

	public function __construct(array $options = array())
	{
		parent::__construct($options);
		$this->setTable('admin::com.beer.table.viewpeople');
	}

	protected function _buildQueryWhere(KDatabaseQuery $query)
	{
		$state = $this->getState();

		if($state->enabled) {
			$query->where('tbl.enabled','=', $state->enabled);
		}

		if ( $state->beer_department_id) {
			$query->where('tbl.beer_department_id','=', $state->beer_department_id);
		}

		if ( $state->beer_office_id) {
			$query->where('tbl.beer_office_id','=', $state->beer_office_id);
		}

		if ( $state->search) {
			$state->search = '%'.$state->search.'%';

			$query->where('tbl.firstname', 'LIKE',  $state->search)
				  ->where('tbl.lastname', 'LIKE', $state->search, 'or')
				  ->where('tbl.bio', 'LIKE', $state->search, 'or');
		}

	}


}