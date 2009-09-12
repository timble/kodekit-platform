<?php
class HarbourModelBoats extends KModelTable
{
	public function getList()
	{
		$list = parent::getList(); 
		foreach($list as $item) {
			$item->link = 'index.php?option=com_harbour&view=boat&id='.$item->id;
		}
		return $list;
	}
	
  	protected function _buildQueryWhere(KDatabaseQuery $query)
    {
       	$search     = $this->_state->search;
        
       	if ($search) {
         	$query->where('tbl.name', 'LIKE', '%'.$search.'%');
       	}
    }
}
