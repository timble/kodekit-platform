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

	public function getFilters()
	{
		$filter = parent::getFilters();
		
		$filter['state']		= KRequest::get('post.filter_state', 'string');
		$filter['search']   	= KRequest::get('post.search', 'string');

		return $filter;
    }
}