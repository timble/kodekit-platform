<?php

class ComAttachmentsModelAttachments extends ComDefaultModelDefault
{
	public function __construct(KConfig $config)
	{
		parent::__construct($config);
		
		$this->_state
			->insert('row', 'int')
		 	->insert('table', 'string');
	}

	protected function _buildQueryColumns(KDatabaseQuerySelect $query)
	{
		if(!$this->_state->isUnique()) {
			$query->columns(array('count' => 'COUNT(relations.attachments_attachment_id)'))
				->columns('table')
				->columns('row');
		}
		
		return parent::_buildQueryColumns($query);
	}
	
	protected function _buildQueryGroup(KDatabaseQuerySelect $query)
	{	
		if(!$this->_state->isUnique()) {
			$query->group('relations.attachments_attachment_id');
		}
		
		return parent::_buildQueryGroup($query);
	}	
	
	protected function _buildQueryJoins(KDatabaseQuerySelect $query)
	{
		if(!$this->_state->isUnique()) {
			$query->join(array('relations' => 'attachments_relations'), 'relations.attachments_attachment_id = tbl.attachments_attachment_id', 'LEFT');
		}
		
		return parent::_buildQueryJoins($query);
	}	
	
	protected function _buildQueryWhere(KDatabaseQuerySelect $query)
	{
		if(!$this->_state->isUnique()) 
		{
			if($this->_state->table) {
				$query->where('relations.table = :table')->bind(array('table' => $this->_state->table));
			}
		
			if($this->_state->row) {
				$query->where('relations.row IN :row')->bind(array('row' => (array) $this->_state->row));
			}
		}
		
		parent::_buildQueryWhere($query);
	}	
}