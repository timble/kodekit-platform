<?php

class ComUsersModelGroups_users extends ComDefaultModelDefault
{	
	public function __construct(KConfig $config)
	{
	    parent::__construct($config);
	    
	    $this->getState()
	        ->insert('user' , 'int')
	        ->insert('group' , 'int');
	}
	
	protected function _buildQueryColumns(KDatabaseQuerySelect $query)
	{
		parent::_buildQueryColumns($query);
	
		$query->columns(array(
			'group_name'    => 'group.name'
		));
	}
	
	protected function _buildQueryJoins(KDatabaseQuerySelect $query)
	{
		$query->join(array('group' => 'users_groups'), 'group.users_group_id = tbl.users_group_id');
	}
	
	protected function _buildQueryWhere(KDatabaseQuerySelect $query)
	{
	    parent::_buildQueryWhere($query);
		$state = $this->getState();
		
		if ($state->user) {
			$query->where('tbl.users_user_id = :user')->bind(array('user' => $state->user));
		}
		
		if ($state->group) {
			$query->where('tbl.users_group_id = :group')->bind(array('group' => $state->group));
		}
	}
}