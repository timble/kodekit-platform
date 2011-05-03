<?php
/** $Id: logs.php 1510 2010-10-02 12:46:52Z daviddeutsch $ */

class ComLogsModelLogs extends KModelTable
{
	protected $_column;
	
	public function __construct(KConfig $config)
	{
		$config->table_behaviors = array('creatable');
		
		parent::__construct($config);
		
		$this->_state
			->insert('application', 'cmd')
			->insert('type', 'cmd')
			->insert('package', 'cmd')
			->insert('name', 'cmd')
			->insert('action', 'cmd')
			->insert('user', 'cmd');
		
		$this->_state
			->remove('sort')->insert('sort', 'cmd', 'created_on')
			->remove('direction')->insert('direction', 'word', 'desc');
	}
	
	protected function _buildQueryColumns(KDatabaseQuery $query)
	{
		parent::_buildQueryColumns($query);
		
		$query->select('users.name AS created_by_name');
	}
	
	protected function _buildQueryJoins(KDatabaseQuery $query)
	{
		$query->join('LEFT', 'users AS users', 'users.id = tbl.created_by');
	}
	
	protected function _buildQueryWhere(KDatabaseQuery $query)
	{
		parent::_buildQueryWhere($query);
		
		if ($this->_state->application) {
			$query->where('tbl.application', '=', $this->_state->application);
		}
		
		if ($this->_state->type) {
			$query->where('tbl.type', '=', $this->_state->type);
		}
		
		if ($this->_state->package) {
			$query->where('tbl.package', '=', $this->_state->package);
		}
		
		if ($this->_state->name) {
			$query->where('tbl.name', '=', $this->_state->name);
		}
		
		if ($this->_state->action) {
			$query->where('tbl.action', 'IN', $this->_state->action);
		}
		
		if ($this->_state->user) {
			$query->where('tbl.created_by', '=', $this->_state->user);
		}
	}
}