<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Component\Users;

use Nooku\Library;

/**
 * Users Model
 *
 * @author  Gergo Erdosi <http://github.com/gergoerdosi>
 * @package Nooku\Component\Users
 */
class ModelUsers extends Library\ModelDatabase
{
	public function __construct(Library\ObjectConfig $config)
	{
		parent::__construct($config);

        $this->getState()
            ->insert('group'      , 'int')
            ->insert('role'       , 'int')
            ->insert('group_tree' , 'boolean', false)
            ->insert('enabled'    , 'boolean')
            ->insert('visited'    , 'boolean')
            ->insert('authentic'  , 'boolean');
	}

    protected function _initialize(Library\ObjectConfig $config)
    {
        $config->append(array(
            'behaviors' => array('searchable' => array('columns' => 'name')),
        ));

        parent::_initialize($config);
    }

    protected function _buildQueryColumns(Library\DatabaseQuerySelect $query)
	{
	    parent::_buildQueryColumns($query);
	    $state = $this->getState();

	    $query->columns(array(
	    	'authentic'  => 'IF(tbl.users_user_id IS NOT NULL, 1, 0)',
	    	'role_name' => 'role.name'
	    ));
	    
	    if($state->authentic)
        {
	        $query->columns(array(
	        	'session_path'   => 'session.path',
                'session_domain' => 'session.domain',
	        	'session_time'     => 'session.time',
	        	'session_id'     => 'session.users_session_id'
	        ));
	    }
	}

	protected function _buildQueryJoins(Library\DatabaseQuerySelect $query)
	{
	    $state = $this->getState();
	    
        $query->join(array('session' => 'users_sessions'), 'tbl.email = session.email', $state->authentic ? 'RIGHT' : 'LEFT');
        $query->join(array('role' => 'users_roles'), 'role.users_role_id = tbl.users_role_id');
        $query->join(array('group' => 'users_groups_users'), 'group.users_user_id = tbl.users_user_id');
	}

	protected function _buildQueryWhere(Library\DatabaseQuerySelect $query)
	{
		parent::_buildQueryWhere($query);

        $state = $this->getState();
		
		if ($state->group)
        {
		    $query->where('group.users_group_id = :group_id')
                  ->bind(array('group_id' => $state->group));
		}
		
		if ($state->role)
		{
		    $query->where('tbl.users_role_id '.($state->group_tree ? '>=' : '=').' :role_id')
		          ->bind(array('role_id' => $state->role));
		}
        
        if (is_bool($state->enabled))
        {
            $query->where('tbl.enabled = :enabled')
                   ->bind(array('enabled' => $state->enabled));
        }

        if (is_bool($state->authentic))
        {
            if($state->authentic  === true) {
                $query->where('tbl.users_user_id IS NOT NULL');
            }

            if($state->authentic  === false) {
                $query->where('tbl.users_user_id IS NULL');
            }
        }
        
        if (is_bool($state->visited))
        {
            $query->where('last_visited_on '.($state->visited ? '!=' : '=').' :last_visited_on')
                  ->bind(array('last_visited_on', '0000-00-00 00:00:00'));
        }
    }
}