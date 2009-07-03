<?php
class BeerModelPeople extends KModelTable
{
	protected function _buildQueryFields(KDatabaseQuery $query)
	{
		$query->select('tbl.*' )
			->select('department.title AS department')
			->select('office.title AS office');
	}

	protected function _buildQueryJoins(KDatabaseQuery $query)
	{
		$query->join('left', 'beer_departments AS department', 'department.beer_department_id = tbl.beer_department_id')
			->join('left', 'beer_offices AS office', 'office.beer_office_id = tbl.beer_office_id');
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
	
	public function getList()
	{
		$list = parent::getList();
		foreach($list as $item)
		{
			$item->name = $item->firstname .' '. $item->middlename .' '. $item->lastname;
		}
		return $list;
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