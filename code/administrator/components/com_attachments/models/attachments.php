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

	protected function _buildQueryColumns(KDatabaseQuery $query)
	{
		if(!$this->_state->isUnique()) {
			$query->select('COUNT(relations.attachments_attachment_id) AS count')
				->select('table')
				->select('row');
		}
		
		return parent::_buildQueryColumns($query);
	}
	
	protected function _buildQueryGroup(KDatabaseQuery $query)
	{	
		if(!$this->_state->isUnique()) {
			$query->group('relations.attachments_attachment_id');
		}
		
		return parent::_buildQueryGroup($query);
	}	
	
	protected function _buildQueryJoins(KDatabaseQuery $query)
	{
		if(!$this->_state->isUnique()) {
			$query->join('LEFT', 'attachments_relations AS relations', 'relations.attachments_attachment_id = tbl.attachments_attachment_id');
		}
		
		return parent::_buildQueryJoins($query);
	}	
	
	protected function _buildQueryWhere(KDatabaseQuery $query)
	{
		if(!$this->_state->isUnique()) 
		{
			if($this->_state->table) {
				$query->where('relations.table','=', $this->_state->table);
			}
		
			if($this->_state->row) {
				$query->where('relations.row', 'IN',  $this->_state->row);
			}
		}
		
		parent::_buildQueryWhere($query);
	}	
}