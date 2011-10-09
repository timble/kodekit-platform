<?php
/** $Id$ */

class ComLogsModelLogs extends ComDefaultModelDefault
{
	public function __construct(KConfig $config)
	{
		parent::__construct($config);

		$this->_state
			->insert('application', 'cmd')
			->insert('type', 'cmd')
			->insert('package', 'cmd')
			->insert('name', 'cmd')
			->insert('action', 'cmd')
			->insert('user', 'cmd')
			->insert('distinct', 'boolean', false)
			->insert('column', 'cmd');

		$this->_state->remove('direction')->insert('direction', 'word', 'desc');
		// Force ordering by created_on
		$this->_state->sort = 'created_on';
	}

	protected function _buildQueryColumns(KDatabaseQuery $query)
	{
		if($this->_state->distinct && !empty($this->_state->column)) 
		{
			$query->distinct()
				->select($this->_state->column)
				->select($this->_state->column . ' AS logs_log_id');
		}
		else
		{
			parent::_buildQueryColumns($query);
			$query->select('users.name AS created_by_name');
		}
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

		if ($this->_state->package && !($this->_state->distinct && !empty($this->_state->column))) {
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

	protected function _buildQueryOrder(KDatabaseQuery $query)
	{
		if($this->_state->distinct && !empty($this->_state->column)) 
		{
			$query->order('package', 'asc');
		}
		else parent::_buildQueryOrder($query);

	}
}