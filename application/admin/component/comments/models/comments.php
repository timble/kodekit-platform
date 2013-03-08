<?php

use Nooku\Framework;

class ComCommentsModelComments extends ComBaseModelDefault
{
	public function __construct(Framework\Config $config)
	{
		parent::__construct($config);
		
		$this->_state
			->insert('table', 'cmd')
			->insert('row', 'int');
	}
	
	protected function _buildQueryWhere(Framework\DatabaseQuerySelect $query)
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