<?php
class ComHarbourModelBoats extends KModelTable
{
	public function __construct(array $options = array())
	{
		$options['table_behaviors'] = array('lockable', 'creatable', 'modifiable');
		
		parent::__construct($options);
	}
	
	protected function _buildQueryWhere(KDatabaseQuery $query)
    {
       	$search     = $this->_state->search;
        
       	if ($search) {
         	$query->where('tbl.name', 'LIKE', '%'.$search.'%');
       	}
       	
       	parent::_buildQueryWhere($query);
    }
}
