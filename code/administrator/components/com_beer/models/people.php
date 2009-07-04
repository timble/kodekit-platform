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

		if ( $filter['beer_department_id']) {
			$query->where('tbl.beer_department_id','=', $filter['beer_department_id']);
		}

		if ( $filter['beer_office_id']) {
			$query->where('tbl.beer_office_id','=', $filter['beer_office_id']);
		}

		if ( $filter['enabled'] ) {
			$query->where('tbl.enabled','=', $filter['enabled']);
		}
	}


	public function getFilters()
	{
		$filter = parent::getFilters();

		$filter['enabled']		= KRequest::get('post.enabled', 'string');
		$filter['beer_department_id']	= KRequest::get('post.beer_department_id', 'int');
		$filter['beer_office_id']		= KRequest::get('post.beer_office_id', 'int');
		$filter['search']   	= KRequest::get('post.search', 'string');

		return $filter;
    }
}