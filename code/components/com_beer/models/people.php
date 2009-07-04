<?php
class BeerModelPeople extends KModelTable
{
	public function getList()
	{
		$list = parent::getList();
		foreach($list as $item)
		{
			$item->name = $item->firstname . $item->middlename . $item->lastname;
		}
		return $list;
	}

	protected function _buildQueryWhere(KDatabaseQuery $query)
	{
		$filter = $this->getFilters();

		if ( $filter['search']) {
			$filter['search'] = '%'.$filter['search'].'%';

			$query->where('tbl.firstname', 'LIKE',  $filter['search'])
				  ->where('tbl.middlename', 'LIKE', $filter['search'], 'or')
				  ->where('tbl.lastname', 'LIKE', $filter['search'], 'or');
		}

		if ( $filter['department']) {
			$query->where('beer_department_id','LIKE', $filter['department']);
		}
	}

	public function getFilters()
	{
		$filters = parent::getFilters();

		$filters['office']	= KRequest::get('post.filter_office_id', 'int');
		$filters['department']	= KRequest::get('post.filter_department_id', 'int');
		$filters['search']   		  	= KRequest::get('post.search', 'string');

		return $filters;
    }

    public function getAll()
	{
        // Get the data if it doesn't already exist
        if (!isset($this->_all))
        {
        	$query = $this->_db->getQuery()
        		->select(array('*'));
        	$this->_all = $this->getTable()->fetchRowset($query);
        }

        return $this->_all;
	}
}