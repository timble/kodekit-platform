<?php
/**
 * @version		$Id$
 * @category	Nooku
 * @package		Nooku_Server
 * @subpackage	Users
 * @copyright	Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */

/**
 * Users Model Class
 *
 * @author      Gergo Erdosi <http://nooku.assembla.com/profile/gergoerdosi>
 * @category	Nooku
 * @package		Nooku_Server
 * @subpackage	Users
 */
class ComUsersModelUsers extends ComDefaultModelDefault
{
    /**
     * Constructor.
     *
     * @param   KConfig  An optional KConfig object with configuration options.
     */
	public function __construct(KConfig $config)
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
     * @param   KDatabaseQuery  A query object.
     * @return  void
     */
	protected function _buildQueryColumns(KDatabaseQuerySelect $query)
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
     * @param   KDatabaseQuery  A query object.
     * @return  void
     */
	protected function _buildQueryJoins(KDatabaseQuerySelect $query)
	{
	    $state = $this->getState();
	    
        $query->join(array('session' => 'users_sessions'), 'tbl.email = session.email', $state->loggedin ? 'RIGHT' : 'LEFT');
        $query->join(array('role' => 'users_roles'), 'role.users_role_id = tbl.users_role_id');
        $query->join(array('group' => 'users_groups_users'), 'group.users_user_id = tbl.users_user_id');
	}

	/**
     * Builds a WHERE clause for the query.
     *
     * @param   KDatabaseQuery  A query object.
     * @return  void
     */
	protected function _buildQueryWhere(KDatabaseQuerySelect $query)
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