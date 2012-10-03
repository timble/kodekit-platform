<?php

class ComUsersModelGroups_users extends ComDefaultModelDefault
{	
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
}