<?php
class ComHarbourModelBoats extends KModelTable
{
  	protected function _buildQueryWhere(KDatabaseQuery $query)
    {
       	$search     = $this->_state->search;
        
       	if ($search) {
         	$query->where('tbl.name', 'LIKE', '%'.$search.'%');
       	}
    }
}
