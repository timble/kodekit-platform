<?php
abstract class BeerModelGroups extends KModelTable
{
	public function __construct(array $options = array())
	{
		parent::__construct($options);
		$this->setTable('admin::com.beer.table.view'.$this->getClassName('suffix'));
	}

	protected function _buildQueryWhere(KDatabaseQuery $query)
	{
		$filter = $this->getFilters();

		if($filter['search']) {
			$query->where('tbl.title', 'LIKE',  '%'.$filter['search'].'%');
		}

		if($filter['state']) {
			$query->where('tbl.enabled','=', $filter['state']);
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

		$filter['state']		= $this->getState('state');
		$filter['search']   	= $this->getState('search');
		$filter['department']	= $this->getState('department_id');
		$filter['office']		= $this->getState('office_id');

		return $filter;
    }
}