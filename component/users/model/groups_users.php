<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

namespace Nooku\Component\Users;

use Nooku\Library;

/**
 * Group Users Model
 *
 * @author  Tom Janssens <http://nooku.assembla.com/profile/tomjanssens>
 * @package Nooku\Component\Users
 */
class ModelGroups_users extends Library\ModelTable
{
	protected function _buildQueryColumns(Library\DatabaseQuerySelect $query)
	{
		parent::_buildQueryColumns($query);
	
		$query->columns(array(
			'group_name'    => 'group.name'
		));
	}
	
	protected function _buildQueryJoins(Library\DatabaseQuerySelect $query)
	{
		$query->join(array('group' => 'users_groups'), 'group.users_group_id = tbl.users_group_id');
	}
	
	protected function _buildQueryWhere(Library\DatabaseQuerySelect $query)
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