<?php
/**
 * Business Enterprise Employee Repository (B.E.E.R)
 * Developed for Brian Teeman's Developer Showdown, using Nooku Framework
 * @version		$Id$
 * @package		Beer
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
		$filter = $this->getFilters();

		if($filter['enabled']) {
			$query->where('tbl.enabled','=', $filter['enabled']);
		}

		if ( $filter['beer_department_id']) {
			$query->where('tbl.beer_department_id','=', $filter['beer_department_id']);
		}

		if ( $filter['beer_office_id']) {
			$query->where('tbl.beer_office_id','=', $filter['beer_office_id']);
		}

		if ( $filter['search']) {
			$filter['search'] = '%'.$filter['search'].'%';

			$query->where('tbl.firstname', 'LIKE',  $filter['search'])
				  ->where('tbl.lastname', 'LIKE', $filter['search'], 'or')
				  ->where('tbl.bio', 'LIKE', $filter['search'], 'or');
		}

	}


	public function getFilters()
	{
		$filter = parent::getFilters();

		$filter['enabled']				= $this->getState('enabled');
		$filter['beer_department_id']	= $this->getState('beer_department_id');
		$filter['beer_office_id']		= $this->getState('beer_office_id');
		$filter['search']   			= $this->getState('search');

		return $filter;
    }
}