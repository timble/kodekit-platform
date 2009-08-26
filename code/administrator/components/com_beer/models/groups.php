<?php
/**
 * Business Enterprise Employee Repository (B.E.E.R)
 * @version		$Id$
 * @package		Beer
 * @copyright	Copyright (C) 2009 Nooku. All rights reserved.
 * @license 	GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.nooku.org
 */

/**
 * Because Offices and departments work basically in the same way, we have an
 * abstract model to represent them both
 */
abstract class BeerModelGroups extends KModelTable
{
	public function __construct(array $options = array())
	{
		parent::__construct($options);
		$this->setTable('admin::com.beer.table.view'.$this->getIdentifier()->name);
	}

	protected function _buildQueryWhere(KDatabaseQuery $query)
	{
		$filter = $this->getFilters();

		if($filter['search']) {
			$query->where('tbl.title', 'LIKE',  '%'.$filter['search'].'%');
		}

		if($filter['enabled']) {
			$query->where('tbl.enabled','=', $filter['enabled']);
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

		$filter['enabled']		= $this->getState('enabled');
		$filter['search']   	= $this->getState('search');
		$filter['beer_department_id']	= $this->getState('beer_department_id');
		$filter['beer_office_id']		= $this->getState('beer_office_id');

		return $filter;
    }
}