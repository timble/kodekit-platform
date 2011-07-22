<?php
/**
 * @version		$Id$
 * @category	Nooku
 * @package		Nooku_Server
 * @subpackage	Users
 * @copyright	Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
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
class ComUsersModelUsers extends KModelTable
{
    /**
     * Constructor.
     *
     * @param   KConfig  An optional KConfig object with configuration options.
     */
	public function __construct(KConfig $config)
	{
		parent::__construct($config);

        $this->_state
        	->insert('activation'  , 'md5', null, true)
            ->insert('email'       , 'email', null, true)
            ->insert('username'    , 'alnum', null, true)
            ->insert('group_name'  , 'string')
            ->insert('group'       , 'int')
            ->insert('loggedin'    , 'boolean', false);
	}

	/**
     * Builds SELECT columns list for the query.
     *
     * @param   KDatabaseQuery  A query object.
     * @return  void
     */
	protected function _buildQueryColumns(KDatabaseQuery $query)
	{
	    parent::_buildQueryColumns($query);
	    
	    $state = $this->_state;

	    $query->select('IF(session.session_id IS NOT NULL, 1, 0) AS loggedin');
	    $query->select('IF(tbl.block = 1, 0, 1) AS enabled');
	    
	    if($state->loggedin) {
	        $query->select(array('session.client_id AS loggedin_client_id', 'session.time AS loggedin_on', 'session.session_id AS loggedin_session_id'));
	    }
	}
	
	/**
     * Builds LEFT JOINS clauses for the query.
     *
     * @param   KDatabaseQuery  A query object.
     * @return  void
     */
	protected function _buildQueryJoins(KDatabaseQuery $query)
	{
	    $state = $this->_state;
	    
	    if($state->loggedin) {
			$query->join('RIGHT', 'session AS session', 'tbl.id = session.userid');
        } else {
            $query->join('LEFT', 'session AS session', 'tbl.id = session.userid');
        }
	}

	/**
     * Builds a WHERE clause for the query.
     *
     * @param   KDatabaseQuery  A query object.
     * @return  void
     */
	protected function _buildQueryWhere(KDatabaseQuery $query)
	{
		parent::_buildQueryWhere($query);
		
		$state = $this->_state;

		if($state->group) {
			$query->where('tbl.gid', '=', $state->group);
		}

	    if($state->group_name) {
            // @TODO: Change usertype to group_name when mapping is fixed.
            $query->where('LOWER(tbl.usertype)', '=', $state->group_name);
        }

	    if($state->search) {
            $query->where('name', 'LIKE', '%'.$state->search.'%')
                  ->where('email', 'LIKE', '%'.$state->search.'%', 'OR');
        }
	}
}