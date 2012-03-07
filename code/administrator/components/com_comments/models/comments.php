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
	
	protected function _buildQueryWhere(KDatabaseQuery $query)
	{
		parent::_buildQueryWhere($query);
		
		if(!$this->_state->isUnique()) {
			if($this->_state->table) {
				$query->where('tbl.table','=', $this->_state->table);
			}

			if($this->_state->row) {
				$query->where('tbl.row', '=',  $this->_state->row);
			}
		}
	}
}