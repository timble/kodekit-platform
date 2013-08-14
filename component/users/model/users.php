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
 * Users Model
 *
 * @author  Gergo Erdosi <http://nooku.assembla.com/profile/gergoerdosi>
 * @package Nooku\Component\Users
 */
class ModelUsers extends Library\ModelTable
{
    /**
     * Constructor.
     *
     * @param   ObjectConfig  An optional Library\ObjectConfig object with configuration options.
     */
	public function __construct(Library\ObjectConfig $config)
	{
		parent::__construct($config);

        $this->getState()
            ->insert('group'      , 'int')
            ->insert('role'       , 'int')
            ->insert('group_tree' , 'boolean', false)
            ->insert('enabled'    , 'boolean')
            ->insert('visited'    , 'boolean')
            ->insert('loggedin'   , 'boolean');
	}

	/**
     * Builds SELECT columns list for the query.
     *
     * @param   Library\DatabaseQuerySelect  A query object.
     * @return  void
     */
	protected function _buildQueryColumns(Library\DatabaseQuerySelect $query)
	{
	    parent::_buildQueryColumns($query);
	    $state = $this->getState();

	    $query->columns(array(
	    	'loggedin'  => 'IF(session.users_session_id IS NOT NULL, 1, 0)',
	    	'role_name' => 'role.name'
	    ));
	    
	    if($state->loggedin)
        {
	        $query->columns(array(
	        	'loggedin_application' => 'session.application',
	        	'loggedin_on'          => 'session.time',
	        	'loggedin_session_id'  => 'session.users_session_id'
	        ));
	    }
	}

	/**
     * Builds LEFT JOINS clauses for the query.
     *
     * @param   Library\DatabaseQuerySelect  A query object.
     * @return  void
     */
	protected function _buildQueryJoins(Library\DatabaseQuerySelect $query)
	{
	    $state = $this->getState();
	    
        $query->join(array('session' => 'users_sessions'), 'tbl.email = session.email', $state->loggedin ? 'RIGHT' : 'LEFT');
        $query->join(array('role' => 'users_roles'), 'role.users_role_id = tbl.users_role_id');
        $query->join(array('group' => 'users_groups_users'), 'group.users_user_id = tbl.users_user_id');
	}

	/**
     * Builds a WHERE clause for the query.
     *
     * @param   Library\DatabaseQuerySelect  A query object.
     * @return  void
     */
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
        
        if ($state->loggedin === false) {
            $query->where('loggedin IS NULL');
        }
        
        if (is_bool($state->visited))
        {
            $query->where('last_visited_on '.($state->visited ? '!=' : '=').' :last_visited_on')
                  ->bind(array('last_visited_on', '0000-00-00 00:00:00'));
        }

	    if ($state->search)
        {
	        $query->where('tbl.name LIKE :search')->bind(array('search' => '%'.$state->search.'%'));
        }
    }
}