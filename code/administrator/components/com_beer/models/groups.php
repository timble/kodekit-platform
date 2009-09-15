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
		$state = $this->_state;
		
		if($state->search) {
			$query->where('tbl.title', 'LIKE',  '%'.$state->search.'%');
		}

		if($state->enabled) {
			$query->where('tbl.enabled','=', $state->enabled);
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

}