<?php
/**
 * @version     $Id$
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Users
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Sessions Model Class
 *
 * @author      Gergo Erdosi <http://nooku.assembla.com/profile/gergoerdosi>
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Users
 */
class ComUsersModelSessions extends KModelTable
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
            ->insert('application', 'word')
            ->insert('email'      , 'email');

        //@TODO : Add special session id filter
        $this->getState()
            ->insert('id', 'string', null, true);
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

        $query->columns(array(
            'name'     => 'users.name',
            'username' => 'users.username',
            'usertype' => 'users.usertype',
            'gid'      => 'users.gid',
        ));
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

        $query->join(array('users' => 'users'), 'tbl.email = users.email');
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
        
        if ($state->application)
        {
            $query->where('application IN :application')
                  ->bind(array('application' => (array) $state->application));
        }

        if ($state->email)
        {
            $query->where('email IN :email')
                  ->bind(array('email' => (array) $state->email));
        }
    }
}