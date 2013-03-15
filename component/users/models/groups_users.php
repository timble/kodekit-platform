<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git
 */

namespace Nooku\Component\Users;

use Nooku\Framework;

/**
 * Group Users Model
 *
 * @author  Tom Janssens <http://nooku.assembla.com/profile/tomjanssens>
 * @package Nooku\Component\Users
 */
class ModelGroups_users extends Framework\ModelTable
{
	protected function _buildQueryColumns(Framework\DatabaseQuerySelect $query)
	{
		parent::_buildQueryColumns($query);
	
		$query->columns(array(
			'group_name'    => 'group.name'
		));
	}
	
	protected function _buildQueryJoins(Framework\DatabaseQuerySelect $query)
	{
		$query->join(array('group' => 'users_groups'), 'group.users_group_id = tbl.users_group_id');
	}
	
	protected function _buildQueryWhere(Framework\DatabaseQuerySelect $query)
	{
	    parent::_buildQueryWhere($query);
		$state = $this->getState();
		
		if ($user_id = $state->user_id) {
			$query->where('tbl.users_user_id = :user_id')->bind(array('user_id' => $user_id));
		}
		
		if ($group_id = $state->group_id) {
			$query->where('tbl.users_group_id = :group_id')->bind(array('group_id' => $group_id));
		}
	}
}