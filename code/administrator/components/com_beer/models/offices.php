<?php
class BeerModelOffices extends KModelTable
{
	protected function _buildQueryFields(KDatabaseQuery $query)
	{
		$query->select('tbl.*');
	}
	
	protected function _buildQueryWhere(KDatabaseQuery $query)
	{
		$filter = $this->getFilters();

		if ( $filter['search']) {
			$filter['search'] = '%'.$filter['search'].'%';

			$query->where('tbl.title', 'LIKE',  $filter['search']);
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
		$filter['search']   	= KRequest::get('post.search', 'string');

		return $filter;
    }
}