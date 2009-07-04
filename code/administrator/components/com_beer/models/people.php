<?php
class BeerModelPeople extends KModelTable
{

	public function __construct(array $options = array())
	{
		$options['table'] = 'admin::com.beer.table.viewpeople';
		parent::__construct($options);
	}

	protected function _buildQueryWhere(KDatabaseQuery $query)
	{
		$filter = $this->getFilters();

		if ( $filter['search']) {
			$filter['search'] = '%'.$filter['search'].'%';

			$query->where('tbl.firstname', 'LIKE',  $filter['search'])
				  ->where('tbl.lastname', 'LIKE', $filter['search'], 'or');
		}

		if ( $filter['department']) {
			$query->where('tbl.beer_department_id','=', $filter['department']);
		}

		if ( $filter['office']) {
			$query->where('tbl.beer_office_id','=', $filter['office']);
		}

		if ( $filter['state'] ) {
			if ( $filter['state'] == 'P' ) {
				$query->where('tbl.enabled','=', 1);
			} else if ($filter['state'] == 'U' ) {
				$query->where('tbl.enabled','=', 0);
			}
		}
	}


	public function getFilters()
	{
		$filter = parent::getFilters();

		$filter['state']		= KRequest::get('post.filter_state', 'string');
		$filter['department']	= KRequest::get('post.filter_department_id', 'int');
		$filter['office']		= KRequest::get('post.filter_office_id', 'int');
		$filter['search']   	= KRequest::get('post.search', 'string');

		return $filter;
    }
}