<?php
class ComCommentsModelComments extends ComDefaultModelDefault
{
	public function __construct(KConfig $config)
	{
		parent::__construct($config);
		
		$this->_state
			->insert('table', 'cmd')
			->insert('row', 'int');
	}
	
	protected function _buildQueryWhere(KDatabaseQuerySelect $query)
	{
		parent::_buildQueryWhere($query);
		
		if(!$this->_state->isUnique()) {
			if($this->_state->table) {
				$query->where('tbl.table = :table')->bind(array('table' => $this->_state->table));
			}

			if($this->_state->row) {
				$query->where('tbl.row = :row')->bind(array('row' => $this->_state->row));
			}
		}
	}
}